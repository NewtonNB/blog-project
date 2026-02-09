import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { FiHome, FiFolder, FiLayout, FiTrash2, FiLogOut, FiMenu, FiX } from 'react-icons/fi';

const Navbar = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const handleLogout = () => {
    logout();
    setMobileMenuOpen(false);
    navigate('/');
  };

  const closeMobileMenu = () => {
    setMobileMenuOpen(false);
  };

  return (
    <nav className="glass-effect sticky top-0 z-50 border-b border-gray-100">
      <div className="container mx-auto px-4">
        <div className="flex justify-between items-center py-4">
          {/* Logo */}
          <Link to="/" className="text-2xl sm:text-3xl font-bold gradient-text" onClick={closeMobileMenu}>
            BlogApp
          </Link>

          {/* Desktop Navigation Links */}
          <div className="hidden md:flex space-x-6">
            <Link to="/" className="text-gray-700 hover:text-transparent hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 hover:bg-clip-text transition-all font-medium flex items-center gap-2">
              <FiHome className="w-4 h-4" />
              Home
            </Link>
            <Link to="/categories" className="text-gray-700 hover:text-transparent hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 hover:bg-clip-text transition-all font-medium flex items-center gap-2">
              <FiFolder className="w-4 h-4" />
              Categories
            </Link>
            {user && (
              <>
                <Link to="/dashboard" className="text-gray-700 hover:text-transparent hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 hover:bg-clip-text transition-all font-medium flex items-center gap-2">
                  <FiLayout className="w-4 h-4" />
                  Dashboard
                </Link>
                <Link to="/trash" className="text-gray-700 hover:text-transparent hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 hover:bg-clip-text transition-all font-medium flex items-center gap-2">
                  <FiTrash2 className="w-4 h-4" />
                  Trash
                </Link>
              </>
            )}
          </div>

          {/* Desktop Auth Section */}
          <div className="hidden md:flex items-center space-x-4">
            {user ? (
              <div className="flex items-center space-x-4">
                <span className="text-gray-700 text-sm font-medium">Welcome, {user.name}</span>
                <button
                  onClick={handleLogout}
                  className="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 text-sm font-medium flex items-center gap-2"
                >
                  <FiLogOut className="w-4 h-4" />
                  Logout
                </button>
              </div>
            ) : (
              <div className="flex space-x-2">
                <Link
                  to="/login"
                  className="btn-secondary px-4 py-2 text-sm"
                >
                  Login
                </Link>
                <Link
                  to="/register"
                  className="btn-primary px-4 py-2 text-sm"
                >
                  Register
                </Link>
              </div>
            )}
          </div>

          {/* Mobile Menu Button */}
          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            className="md:hidden p-2 rounded-md text-gray-700 hover:bg-gray-100"
          >
            {mobileMenuOpen ? (
              <FiX className="w-6 h-6" />
            ) : (
              <FiMenu className="w-6 h-6" />
            )}
          </button>
        </div>

        {/* Mobile Menu */}
        {mobileMenuOpen && (
          <div className="md:hidden pb-4 border-t border-gray-200 animate-slide-up">
            <div className="flex flex-col space-y-2 pt-4">
              <Link
                to="/"
                onClick={closeMobileMenu}
                className="text-gray-700 hover:text-transparent hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 hover:bg-clip-text px-4 py-2 rounded-md transition-all font-medium flex items-center gap-2"
              >
                <FiHome className="w-4 h-4" />
                Home
              </Link>
              <Link
                to="/categories"
                onClick={closeMobileMenu}
                className="text-gray-700 hover:text-transparent hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 hover:bg-clip-text px-4 py-2 rounded-md transition-all font-medium flex items-center gap-2"
              >
                <FiFolder className="w-4 h-4" />
                Categories
              </Link>
              {user && (
                <>
                  <Link
                    to="/dashboard"
                    onClick={closeMobileMenu}
                    className="text-gray-700 hover:text-transparent hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 hover:bg-clip-text px-4 py-2 rounded-md transition-all font-medium flex items-center gap-2"
                  >
                    <FiLayout className="w-4 h-4" />
                    Dashboard
                  </Link>
                  <Link
                    to="/trash"
                    onClick={closeMobileMenu}
                    className="text-gray-700 hover:text-transparent hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 hover:bg-clip-text px-4 py-2 rounded-md transition-all font-medium flex items-center gap-2"
                  >
                    <FiTrash2 className="w-4 h-4" />
                    Trash
                  </Link>
                </>
              )}
              
              {/* Mobile Auth Section */}
              <div className="pt-4 border-t border-gray-200">
                {user ? (
                  <>
                    <p className="text-gray-700 px-4 py-2 text-sm font-medium">Welcome, {user.name}</p>
                    <button
                      onClick={handleLogout}
                      className="w-full text-left bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2 rounded-lg shadow-md transition-all font-medium flex items-center gap-2"
                    >
                      <FiLogOut className="w-4 h-4" />
                      Logout
                    </button>
                  </>
                ) : (
                  <div className="flex flex-col space-y-2">
                    <Link
                      to="/login"
                      onClick={closeMobileMenu}
                      className="btn-secondary text-center"
                    >
                      Login
                    </Link>
                    <Link
                      to="/register"
                      onClick={closeMobileMenu}
                      className="btn-primary text-center"
                    >
                      Register
                    </Link>
                  </div>
                )}
              </div>
            </div>
          </div>
        )}
      </div>
    </nav>
  );
};

export default Navbar;