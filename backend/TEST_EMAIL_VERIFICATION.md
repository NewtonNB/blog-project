# Testing Email Verification

## Quick Test Guide

Follow these steps to test the email verification feature:

### Step 1: Configure Mailtrap

1. Go to [https://mailtrap.io](https://mailtrap.io) and sign up
2. Get your SMTP credentials from the inbox
3. Update `blog-api/.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username_here
MAIL_PASSWORD=your_password_here
MAIL_ENCRYPTION=tls
```

4. Clear cache:
```bash
cd blog-api
php artisan config:clear
```

### Step 2: Test Registration (Sends Email)

**Using cURL:**
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register ^
  -H "Content-Type: application/json" ^
  -d "{\"name\":\"Test User\",\"email\":\"test@example.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}"
```

**Using Postman:**
- Method: POST
- URL: `http://127.0.0.1:8000/api/auth/register`
- Body (JSON):
```json
{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Expected Response:**
```json
{
  "success": true,
  "message": "User registered successfully. Please check your email to verify your account.",
  "data": {
    "user": {
      "name": "Test User",
      "email": "test@example.com",
      "email_verified_at": null
    },
    "token": "...",
    "email_verified": false
  }
}
```

### Step 3: Check Mailtrap Inbox

1. Go to your Mailtrap inbox
2. You should see a new email: "Verify Your Email Address - Blog API"
3. Open the email
4. You'll see a "Verify Email Address" button

### Step 4: Try Login Before Verification (Should Fail)

**Using cURL:**
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"test@example.com\",\"password\":\"password123\"}"
```

**Expected Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "Please verify your email address before logging in.",
  "email_verified": false
}
```

### Step 5: Verify Email

**Option A: Click Link in Mailtrap**
- Click the "Verify Email Address" button in the email
- Copy the verification URL

**Option B: Use the Verification URL Directly**

The URL format is:
```
http://127.0.0.1:8000/api/email/verify/{user_id}/{hash}?expires={timestamp}&signature={signature}
```

**Using cURL (copy full URL from email):**
```bash
curl "http://127.0.0.1:8000/api/email/verify/1/abc123...?expires=...&signature=..."
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Email verified successfully! You can now login."
}
```

### Step 6: Login After Verification (Should Succeed)

**Using cURL:**
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"test@example.com\",\"password\":\"password123\"}"
```

**Expected Response (200 OK):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Test User",
      "email": "test@example.com",
      "email_verified_at": "2026-02-08T10:30:00.000000Z"
    },
    "token": "2|...",
    "token_type": "Bearer"
  }
}
```

### Step 7: Test Resend Verification Email

**Using cURL:**
```bash
curl -X POST http://127.0.0.1:8000/api/auth/resend-verification ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"test@example.com\"}"
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Verification email sent successfully"
}
```

## Testing Checklist

- [ ] Mailtrap account created and configured
- [ ] .env file updated with Mailtrap credentials
- [ ] Configuration cache cleared
- [ ] User registration successful
- [ ] Verification email received in Mailtrap
- [ ] Login blocked before verification (403 error)
- [ ] Email verification successful
- [ ] Login successful after verification
- [ ] Resend verification email works

## Common Issues

### Email Not Received in Mailtrap

**Solution:**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Verify mail configuration
php artisan tinker
>>> config('mail.mailers.smtp')
```

### Verification Link Expired

**Solution:**
```bash
# Resend verification email
curl -X POST http://127.0.0.1:8000/api/auth/resend-verification \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com"}'
```

### Manually Verify User (For Testing)

**Solution:**
```bash
php artisan tinker
>>> $user = App\Models\User::where('email', 'test@example.com')->first();
>>> $user->markEmailAsVerified();
>>> $user->email_verified_at
```

## What's Next?

After testing email verification:

1. **Add Frontend Pages:**
   - Email verification success page
   - Email verification pending page
   - Resend verification button

2. **Add Password Reset:**
   - Forgot password endpoint
   - Reset password with email link

3. **Add Email Notifications:**
   - Welcome email after verification
   - New post published notification
   - Comment notifications

4. **Production Setup:**
   - Switch from Mailtrap to real email service
   - Use queue for sending emails
   - Add rate limiting for resend verification

---

**All tests passing?** You're ready to integrate email verification into your frontend! 🎉
