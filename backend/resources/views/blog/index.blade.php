<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Blog</title>
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
            position: sticky;
            top: 0;
            z-index: 100;
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
            align-items: center;
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
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
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

        .hero {
            text-align: center;
            margin: 3rem 0 4rem 0;
            padding: 3rem 2rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 24px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .hero p {
            font-size: 1.25rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
        }

        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .post-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            cursor: pointer;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .post-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .post-image {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .post-content {
            padding: 2rem;
        }

        .post-meta {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #9ca3af;
            margin-bottom: 1rem;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .category-tag {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-published {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-draft {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .post-title {
            font-size: 1.5rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: #1f2937;
            margin-bottom: 1rem;
            line-height: 1.3;
            letter-spacing: -0.3px;
        }

        .post-excerpt {
            color: #6b7280;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .post-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }

        .author {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .author-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.75rem;
        }

        .read-more {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .read-more:hover {
            color: #764ba2;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #4a5568;
            font-weight: 500;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            .posts-grid {
                grid-template-columns: 1fr;
            }
            .navbar-content {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-content">
            <a href="/" class="logo">PHP Blog</a>
            
            <div class="nav-links">
                <a href="/">Home</a>
                @auth
                    <a href="/dashboard">Dashboard</a>
                @endauth
            </div>
            
            <div>
                @auth
                    <div class="user-info">
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <span>Welcome, {{ auth()->user()->name }}</span>
                        <form action="/logout" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="/login" class="btn btn-primary">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="hero">
            <h1>Welcome to PHP Blog</h1>
            <p>Discover amazing stories, insights, and knowledge from our community of talented writers. Built with PHP & Laravel.</p>
        </div>

        <div class="posts-grid">
            @forelse($posts as $post)
                <article class="post-card">
                    <div class="post-image">
                        {{ $post->category->name }}
                    </div>
                    
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="category-tag">{{ $post->category->name }}</span>
                            <span>•</span>
                            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                            <span>•</span>
                            <span class="status-badge status-{{ $post->status }}">{{ $post->status }}</span>
                        </div>
                        
                        <h3 class="post-title">{{ $post->title }}</h3>
                        
                        @if($post->excerpt)
                            <p class="post-excerpt">{{ Str::limit($post->excerpt, 150) }}</p>
                        @endif
                        
                        <div class="post-footer">
                            <div class="author">
                                <div class="author-avatar">{{ strtoupper(substr($post->user->name, 0, 1)) }}</div>
                                <span>By {{ $post->user->name }}</span>
                            </div>
                            <a href="/posts/{{ $post->slug }}" class="read-more">Read more →</a>
                        </div>
                    </div>
                </article>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #6b7280;">
                    <h3>No posts found</h3>
                    <p>Check back later for new content!</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>