<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PHP Blog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 24px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            font-size: 2rem;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2rem;
            letter-spacing: -0.5px;
        }

        h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }

        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }

        .error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .input-error {
            border-color: #dc2626 !important;
        }

        .success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #16a34a;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .demo-box {
            padding: 1.5rem;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            text-align: center;
            margin-top: 1.5rem;
        }

        .demo-box p {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }

        .demo-box small {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link a:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">PHP Blog</div>
        <h2>Welcome Back!</h2>

        @if($errors->any())
            <div class="error">
                <strong>Validation Errors:</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="error">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="success">
                <strong>Success:</strong> {{ session('success') }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address: <span style="color: #dc2626;">*</span></label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', 'tukamuhebwanewton@gmail.com') }}" 
                    required
                    placeholder="Enter your email"
                    class="@error('email') input-error @enderror"
                >
                @error('email')
                    <div class="error-message">
                        <strong>!</strong> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password: <span style="color: #dc2626;">*</span></label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    value="password123"
                    required
                    placeholder="Enter your password"
                    class="@error('password') input-error @enderror"
                >
                @error('password')
                    <div class="error-message">
                        <strong>!</strong> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember"
                        style="margin-right: 0.5rem; width: auto; cursor: pointer;"
                    >
                    <span style="font-weight: 500;">Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn">Sign In</button>
        </form>

        <div class="demo-box">
            <p>Demo Credentials:</p>
            <small>
                Email: tukamuhebwanewton@gmail.com<br>
                Password: password123
            </small>
        </div>

        <div class="contact-box" style="margin-top: 1.5rem; padding: 1rem; background: rgba(102, 126, 234, 0.05); border-radius: 12px; text-align: center;">
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Need help?</p>
            <p style="font-size: 0.875rem; color: #374151;">
                <strong>Email:</strong> tukamuhebwanewton@gmail.com<br>
                <strong>Phone:</strong> <span id="phone-display">+256 XXX XXX XXX</span>
            </p>
        </div>

        <div class="back-link">
            <a href="/">Back to Home</a>
        </div>
    </div>
</body>
</html>