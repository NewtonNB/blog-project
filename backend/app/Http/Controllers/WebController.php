<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    /**
     * Display the home page with all posts
     */
    public function index()
    {
        $posts = Post::with(['user', 'category'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('blog.index', compact('posts'));
    }

    /**
     * Show the login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        
        return view('blog.login');
    }

    /**
     * Handle login request with validation
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended('/dashboard')
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('email'))
            ->with('error', 'Invalid login credentials. Please try again.');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * Show the dashboard (protected)
     */
    public function dashboard()
    {
        $postsCount = Post::count();
        
        return view('blog.dashboard', compact('postsCount'));
    }
}