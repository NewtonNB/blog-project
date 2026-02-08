<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>React Blog App</title>
    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-router-dom@6.8.0/dist/umd/react-router-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <style>
        /* Enhanced React Blog Styles */
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

        body {
          font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          min-height: 100vh;
          line-height: 1.6;
        }

        .app {
          min-height: 100vh;
          background: #f8fafc;
        }

        /* Enhanced Navbar */
        .navbar {
          background: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(10px);
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
          padding: 1rem 2rem;
          position: sticky;
          top: 0;
          z-index: 100;
          border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .navbar-content {
          display: flex;
          justify-content: space-between;
          align-items: center;
          max-width: 1200px;
          margin: 0 auto;
        }

        .navbar .logo {
          font-size: 1.8rem;
          font-weight: 800;
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          background-clip: text;
          text-decoration: none;
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
          transform: translateY(-1px);
        }

        .auth-section {
          display: flex;
          gap: 1rem;
          align-items: center;
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

        /* Enhanced Buttons */
        .btn {
          padding: 0.75rem 1.5rem;
          border-radius: 12px;
          font-weight: 600;
          text-decoration: none;
          border: none;
          cursor: pointer;
          transition: all 0.3s ease;
          display: inline-flex;
          align-items: center;
          gap: 0.5rem;
          font-size: 0.875rem;
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
          backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
          background: #667eea;
          color: white;
          transform: translateY(-2px);
        }

        /* Enhanced Container */
        .container {
          max-width: 1200px;
          margin: 0 auto;
          padding: 2rem;
        }

        /* Enhanced Hero */
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
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          background-clip: text;
          margin-bottom: 1rem;
          line-height: 1.2;
        }

        .hero p {
          font-size: 1.25rem;
          color: #6b7280;
          max-width: 600px;
          margin: 0 auto;
          line-height: 1.6;
        }

        /* Enhanced Posts Grid */
        .posts-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
          gap: 2rem;
          margin-top: 2rem;
        }

        /* Enhanced Post Cards */
        .post-card {
          background: rgba(255, 255, 255, 0.95);
          border-radius: 20px;
          overflow: hidden;
          transition: all 0.4s ease;
          cursor: pointer;
          backdrop-filter: blur(10px);
          border: 1px solid rgba(255, 255, 255, 0.2);
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
          position: relative;
          overflow: hidden;
        }

        .post-image::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: rgba(0, 0, 0, 0.1);
          backdrop-filter: blur(1px);
        }

        .post-image span {
          position: relative;
          z-index: 1;
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
        }

        .category-tag {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          color: white;
          padding: 0.25rem 0.75rem;
          border-radius: 12px;
          font-weight: 600;
          font-size: 0.75rem;
          text-transform: uppercase;
          letter-spacing: 0.5px;
        }

        .post-title {
          font-size: 1.5rem;
          font-weight: 700;
          color: #1f2937;
          margin-bottom: 1rem;
          line-height: 1.3;
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
          display: flex;
          align-items: center;
          gap: 0.25rem;
          transition: all 0.3s ease;
        }

        .read-more:hover {
          color: #764ba2;
          transform: translateX(2px);
        }

        /* Enhanced Loading */
        .loading {
          text-align: center;
          padding: 4rem;
          background: rgba(255, 255, 255, 0.9);
          border-radius: 20px;
          backdrop-filter: blur(10px);
          margin: 2rem 0;
        }

        .spinner {
          width: 50px;
          height: 50px;
          border: 4px solid #f3f4f6;
          border-top: 4px solid #667eea;
          border-radius: 50%;
          animation: spin 1s linear infinite;
          margin: 0 auto 1rem;
        }

        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }

        /* Enhanced Error */
        .error {
          background: rgba(254, 242, 242, 0.95);
          border: 2px solid #fca5a5;
          color: #dc2626;
          padding: 2rem;
          border-radius: 16px;
          margin: 2rem 0;
          backdrop-filter: blur(10px);
        }

        .error h3 {
          margin-bottom: 1rem;
          font-size: 1.25rem;
        }

        /* Enhanced Forms */
        .login-form {
          max-width: 450px;
          margin: 3rem auto;
          background: rgba(255, 255, 255, 0.95);
          padding: 3rem;
          border-radius: 24px;
          backdrop-filter: blur(10px);
          box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
          border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-form h2 {
          text-align: center;
          margin-bottom: 2rem;
          font-size: 2rem;
          font-weight: 700;
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          background-clip: text;
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

        /* Enhanced Dashboard */
        .dashboard {
          background: rgba(255, 255, 255, 0.95);
          padding: 3rem;
          border-radius: 24px;
          backdrop-filter: blur(10px);
          box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
          border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dashboard h2 {
          font-size: 2.5rem;
          font-weight: 700;
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          background-clip: text;
          margin-bottom: 2rem;
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
          backdrop-filter: blur(10px);
          border: 1px solid rgba(255, 255, 255, 0.3);
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
          font-weight: 600;
          margin-bottom: 0.5rem;
        }

        .stat-card p {
          color: #6b7280;
          font-size: 0.875rem;
        }

        /* Status Badges */
        .status-badge {
          display: inline-block;
          padding: 0.25rem 0.75rem;
          border-radius: 12px;
          font-size: 0.75rem;
          font-weight: 600;
          text-transform: uppercase;
          letter-spacing: 0.5px;
        }

        .status-published {
          background: linear-gradient(135deg, #10b981 0%, #059669 100%);
          color: white;
        }

        .status-draft {
          background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
          color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
          .hero h1 {
            font-size: 2.5rem;
          }
          
          .posts-grid {
            grid-template-columns: 1fr;
          }
          
          .container {
            padding: 1rem;
          }
          
          .navbar {
            padding: 1rem;
          }
          
          .navbar-content {
            flex-direction: column;
            gap: 1rem;
          }
          
          .nav-links {
            gap: 1rem;
          }
        }

        /* Animations */
        @keyframes fadeIn {
          from {
            opacity: 0;
            transform: translateY(20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        .fade-in {
          animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div id="root"></div>

    <script type="text/babel">
        // Enhanced React Blog Application
        const { useState, useEffect, createContext, useContext } = React;
        const { BrowserRouter, Routes, Route, Link, useNavigate, Navigate } = ReactRouterDOM;

        // Auth Context
        const AuthContext = createContext();

        const AuthProvider = ({ children }) => {
          const [user, setUser] = useState(null);
          const [token, setToken] = useState(localStorage.getItem('token'));
          const [loading, setLoading] = useState(false);

          const login = async (email, password) => {
            setLoading(true);
            try {
              const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
              });
              const data = await response.json();
              
              if (data.success) {
                setUser(data.data.user);
                setToken(data.data.token);
                localStorage.setItem('token', data.data.token);
                return true;
              }
              return false;
            } catch (error) {
              console.error('Login error:', error);
              return false;
            } finally {
              setLoading(false);
            }
          };

          const logout = async () => {
            if (token) {
              try {
                await fetch('/api/auth/logout', {
                  method: 'POST',
                  headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                  }
                });
              } catch (error) {
                console.error('Logout error:', error);
              }
            }
            
            setUser(null);
            setToken(null);
            localStorage.removeItem('token');
          };

          const contextValue = { user, token, login, logout, loading };

          return (
            <AuthContext.Provider value={contextValue}>
              {children}
            </AuthContext.Provider>
          );
        };

        const useAuth = () => {
          const context = useContext(AuthContext);
          if (!context) {
            throw new Error('useAuth must be used within an AuthProvider');
          }
          return context;
        };

        // Enhanced Navbar Component
        const Navbar = () => {
          const { user, logout } = useAuth();

          return (
            <nav className="navbar">
              <div className="navbar-content">
                <Link to="/" className="logo">
                  🚀 ReactBlog
                </Link>
                
                <div className="nav-links">
                  <Link to="/">Home</Link>
                  {user && <Link to="/dashboard">Dashboard</Link>}
                </div>
                
                <div className="auth-section">
                  {user ? (
                    <>
                      <div className="user-info">
                        <div className="user-avatar">
                          {user.name.charAt(0).toUpperCase()}
                        </div>
                        <span>Welcome, {user.name}</span>
                      </div>
                      <button className="btn btn-secondary" onClick={logout}>
                        Logout
                      </button>
                    </>
                  ) : (
                    <Link to="/login" className="btn btn-primary">
                      Login
                    </Link>
                  )}
                </div>
              </div>
            </nav>
          );
        };

        // Enhanced Post Card Component
        const PostCard = ({ post }) => {
          return (
            <article className="post-card fade-in">
              <div className="post-image">
                <span>📝 {post.category.name}</span>
              </div>
              
              <div className="post-content">
                <div className="post-meta">
                  <span className="category-tag">{post.category.name}</span>
                  <span>{new Date(post.published_at || post.created_at).toLocaleDateString()}</span>
                  <span className={`status-badge status-${post.status}`}>{post.status}</span>
                </div>
                
                <h3 className="post-title">{post.title}</h3>
                
                {post.excerpt && (
                  <p className="post-excerpt">{post.excerpt.substring(0, 150)}...</p>
                )}
                
                <div className="post-footer">
                  <div className="author">
                    <div className="author-avatar">
                      {post.user.name.charAt(0).toUpperCase()}
                    </div>
                    <span>By {post.user.name}</span>
                  </div>
                  <a href="#" className="read-more">
                    Read more →
                  </a>
                </div>
              </div>
            </article>
          );
        };

        // Enhanced Home Component
        const Home = () => {
          const [posts, setPosts] = useState([]);
          const [loading, setLoading] = useState(true);
          const [error, setError] = useState('');

          useEffect(() => {
          const fetchPosts = async () => {
            try {
              const response = await fetch('/api/posts');
              const data = await response.json();
              
              if (data.success) {
                setPosts(data.data.data);
              } else {
                setError('Failed to load posts');
              }
            } catch (err) {
              setError('Failed to connect to API: ' + err.message);
            } finally {
              setLoading(false);
            }
          };

            fetchPosts();
          }, []);

          if (loading) {
            return (
              <div className="container">
                <div className="loading">
                  <div className="spinner"></div>
                  <p>Loading amazing posts from Laravel API...</p>
                </div>
              </div>
            );
          }

          if (error) {
            return (
              <div className="container">
                <div className="error">
                  <h3>⚠️ Connection Error</h3>
                  <p>{error}</p>
                  <p><strong>Make sure Laravel server is running:</strong> php artisan serve</p>
                  <button 
                    className="btn btn-primary" 
                    onClick={() => window.location.reload()}
                    style={@{marginTop: '1rem'@}}
                  >
                    🔄 Retry Connection
                  </button>
                </div>
              </div>
            );
          }

          return (
            <div className="container">
              <div className="hero">
                <h1>Welcome to ReactBlog</h1>
                <p>Discover amazing stories, insights, and knowledge from our community of talented writers. Built with React & Laravel.</p>
              </div>

              <div className="posts-grid">
                {posts.map(post => (
                  <PostCard key={post.id} post={post} />
                ))}
              </div>
              
              {posts.length === 0 && (
                <div style={{textAlign: 'center', padding: '3rem', color: '#6b7280'}}>
                  <h3>No posts found</h3>
                  <p>Make sure your Laravel backend is running with seeded data.</p>
                </div>
              )}
            </div>
          );
        };

        // Enhanced Login Component
        const Login = () => {
          const [email, setEmail] = useState('john@example.com');
          const [password, setPassword] = useState('password');
          const [error, setError] = useState('');
          const { login, loading } = useAuth();
          const navigate = useNavigate();

          const handleSubmit = async (e) => {
            e.preventDefault();
            setError('');
            
            const success = await login(email, password);
            if (success) {
              navigate('/dashboard');
            } else {
              setError('Invalid email or password. Please try again.');
            }
          };

          return (
            <div className="container">
              <form className="login-form fade-in" onSubmit={handleSubmit}>
                <h2>Welcome Back!</h2>
                
                {error && (
                  <div className="error" style={{marginBottom: '1rem'}}>
                    {error}
                  </div>
                )}
                
                <div className="form-group">
                  <label htmlFor="email">Email Address:</label>
                  <input
                    type="email"
                    id="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required
                    disabled={loading}
                    placeholder="Enter your email"
                  />
                </div>
                
                <div className="form-group">
                  <label htmlFor="password">Password:</label>
                  <input
                    type="password"
                    id="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                    disabled={loading}
                    placeholder="Enter your password"
                  />
                </div>
                
                <button 
                  type="submit" 
                  className="btn btn-primary" 
                  style={{width: '100%', marginBottom: '1rem'}}
                  disabled={loading}
                >
                  {loading ? 'Signing in...' : 'Sign In'}
                </button>
                
                <div style={{
                  padding: '1.5rem', 
                  background: 'rgba(102, 126, 234, 0.1)', 
                  borderRadius: '12px',
                  textAlign: 'center'
                }}>
                  <p style={{fontWeight: '600', color: '#374151', marginBottom: '0.5rem'}}>
                    Demo Credentials:
                  </p>
                  <p style={{color: '#6b7280', fontSize: '0.875rem'}}>
                    Email: john@example.com<br/>
                    Password: password
                  </p>
                </div>
              </form>
            </div>
          );
        };

        // Enhanced Dashboard Component
        const Dashboard = () => {
          const { user, token } = useAuth();

          if (!user || !token) {
            return <Navigate to="/login" replace />;
          }

          return (
            <div className="container">
              <div className="dashboard fade-in">
                <h2>Welcome to Your Dashboard, {user.name}! 🎉</h2>
                
                <div style={{
                  background: 'rgba(16, 185, 129, 0.1)', 
                  padding: '2rem', 
                  borderRadius: '16px', 
                  border: '2px solid rgba(16, 185, 129, 0.2)',
                  marginBottom: '2rem'
                }}>
                  <h3 style={{color: '#065f46', marginBottom: '1rem', display: 'flex', alignItems: 'center', gap: '0.5rem'}}>
                    ✅ Authentication Successful!
                  </h3>
                  <div style={{display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '1rem'}}>
                    <div>
                      <p style={{color: '#047857', fontWeight: '600'}}>User ID: {user.id}</p>
                      <p style={{color: '#047857', fontWeight: '600'}}>Name: {user.name}</p>
                      <p style={{color: '#047857', fontWeight: '600'}}>Email: {user.email}</p>
                    </div>
                    <div>
                      <p style={{color: '#047857', fontSize: '0.875rem'}}>
                        <strong>Token:</strong> {token.substring(0, 20)}...
                      </p>
                    </div>
                  </div>
                </div>
                
                <div className="stats-grid">
                  <div className="stat-card">
                    <div className="stat-icon">⚛️</div>
                    <h4>React Frontend</h4>
                    <p>Modern React with Hooks</p>
                  </div>
                  <div className="stat-card">
                    <div className="stat-icon">🔧</div>
                    <h4>Laravel API</h4>
                    <p>Sanctum Authentication</p>
                  </div>
                  <div className="stat-card">
                    <div className="stat-icon">📝</div>
                    <h4>Blog Posts</h4>
                    <p>15 Posts Available</p>
                  </div>
                  <div className="stat-card">
                    <div className="stat-icon">🎨</div>
                    <h4>Enhanced UI</h4>
                    <p>Beautiful Design</p>
                  </div>
                </div>
                
                <div style={{
                  marginTop: '3rem', 
                  textAlign: 'center',
                  padding: '2rem',
                  background: 'rgba(255, 255, 255, 0.5)',
                  borderRadius: '16px'
                }}>
                  <p style={{color: '#6b7280', marginBottom: '1.5rem', fontSize: '1.1rem'}}>
                    🎉 Your React blog application is fully functional with enhanced UI and authentication!
                  </p>
                  <Link to="/" className="btn btn-primary">
                    ← Explore Blog Posts
                  </Link>
                </div>
              </div>
            </div>
          );
        };

        // Protected Route Component
        const ProtectedRoute = ({ children }) => {
          const { user, token } = useAuth();
          return user && token ? children : <Navigate to="/login" replace />;
        };

        // Main App Component
        const App = () => {
          return (
            <AuthProvider>
              <BrowserRouter>
                <div className="app">
                  <Navbar />
                  <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/login" element={<Login />} />
                    <Route 
                      path="/dashboard" 
                      element={
                        <ProtectedRoute>
                          <Dashboard />
                        </ProtectedRoute>
                      } 
                    />
                  </Routes>
                </div>
              </BrowserRouter>
            </AuthProvider>
          );
        };

        // Render the app
        ReactDOM.render(<App />, document.getElementById('root'));
    </script>
</body>
</html>