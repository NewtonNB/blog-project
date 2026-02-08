<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PHP Blog</title>
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
            line-height: 1.6;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .dashboard {
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 24px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .dashboard h2 {
            font-size: 2.5rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2rem;
            letter-spacing: -0.5px;
        }

        .success-box {
            background: rgba(16, 185, 129, 0.1);
            padding: 2rem;
            border-radius: 16px;
            border: 2px solid rgba(16, 185, 129, 0.2);
            margin-bottom: 2rem;
        }

        .success-box h3 {
            color: #065f46;
            margin-bottom: 1rem;
        }

        .user-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .user-info p {
            color: #047857;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .stat-card h4 {
            color: #1f2937;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
            letter-spacing: -0.3px;
        }

        .stat-card p {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .footer-box {
            margin-top: 3rem;
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 16px;
        }

        .footer-box p {
            color: #6b7280;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.875rem;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-content">
            <a href="/" class="logo">PHP Blog</a>
            
            <div class="nav-links">
                <a href="/">Home</a>
                <a href="/dashboard">Dashboard</a>
            </div>
            
            <form action="/logout" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard">
            @if(session('success'))
                <div style="background: #f0fdf4; border: 2px solid #86efac; color: #16a34a; padding: 1rem; border-radius: 12px; margin-bottom: 2rem;">
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif

            <h2>Welcome to Your Dashboard, {{ auth()->user()->name }}!</h2>
            
            <div class="success-box">
                <h3>Authentication Successful!</h3>
                <div class="user-info">
                    <div>
                        <p>User ID: {{ auth()->user()->id }}</p>
                        <p>Name: {{ auth()->user()->name }}</p>
                        <p>Email: {{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <p>Member Since: {{ auth()->user()->created_at->format('M d, Y') }}</p>
                        <p>Last Updated: {{ auth()->user()->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">PHP</div>
                    <h4>PHP Frontend</h4>
                    <p>Laravel Blade Templates</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">API</div>
                    <h4>Laravel Backend</h4>
                    <p>Session Authentication</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">BLOG</div>
                    <h4>Blog Posts</h4>
                    <p>{{ $postsCount }} Posts Available</p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">UI</div>
                    <h4>Enhanced UI</h4>
                    <p>Beautiful Design</p>
                </div>
            </div>
            
            <div class="footer-box">
                <p>Your PHP blog application is fully functional with session-based authentication!</p>
                <a href="/" class="btn-primary">Explore Blog Posts</a>
            </div>
        </div>
    </div>
</body>
</html>