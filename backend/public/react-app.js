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
      const response = await fetch('http://127.0.0.1:8000/api/auth/login', {
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
        await fetch('http://127.0.0.1:8000/api/auth/logout', {
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

  return (
    <AuthContext.Provider value={{ user, token, login, logout, loading }}>
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
        const response = await fetch('http://127.0.0.1:8000/api/posts');
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
            style={{marginTop: '1rem'}}
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