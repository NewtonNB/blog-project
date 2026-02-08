<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\URL;
use App\Notifications\SendOtpCode;
use Carbon\Carbon;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;

/**
 * AuthController handles user authentication
 * Provides register, login, logout functionality using Laravel Sanctum
 */
class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            // Generate OTP code
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Create new user with validated data
            $user = User::create([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'phone' => $request->validated('phone'),
                'password' => Hash::make($request->validated('password')),
                'bio' => $request->validated('bio'),
                'otp_code' => $otpCode,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
            ]);

            // Send OTP via email
            $user->notify(new SendOtpCode($otpCode));

            // Create API token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully. Please check your email for the verification code.',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'email_verified' => false
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user and create token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Find user by email
            $user = User::where('email', $request->validated('email'))->first();

            // Check if password is correct
            if (!Hash::check($request->validated('password'), $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your email address before logging in.',
                    'email_verified' => false
                ], 403);
            }

            // Create API token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user and revoke token
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revoke the current user's token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get authenticated user profile
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => new UserResource($request->user())
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify user email
     */
    public function verifyEmail(Request $request, $id, $hash): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Check if hash matches
            if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid verification link'
                ], 403);
            }

            // Check if already verified
            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email already verified'
                ], 200);
            }

            // Mark email as verified
            $user->markEmailAsVerified();

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully! You can now login.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email verification failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'otp_code' => 'required|string|size:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            // Check if OTP matches
            if ($user->otp_code !== $request->otp_code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP code'
                ], 400);
            }

            // Check if OTP has expired
            if (Carbon::now()->greaterThan($user->otp_expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP code has expired. Please request a new one.'
                ], 400);
            }

            // Mark email as verified and clear OTP
            $user->email_verified_at = Carbon::now();
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully! You can now login.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'OTP verification failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already verified'
                ], 400);
            }

            // Generate new OTP code
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->otp_code = $otpCode;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            // Send OTP via email
            $user->notify(new SendOtpCode($otpCode));

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend verification code',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}