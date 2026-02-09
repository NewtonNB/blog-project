import React, { createContext, useContext, useState, useEffect } from 'react';
import { authAPI } from '../services/api';

const AuthContext = createContext(undefined);

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Check for existing auth data on mount
    const savedToken = localStorage.getItem('auth_token');
    const savedUser = localStorage.getItem('user');

    if (savedToken && savedUser) {
      setToken(savedToken);
      setUser(JSON.parse(savedUser));
    }
    setLoading(false);
  }, []);

  const login = async (email, password) => {
    try {
      console.log('AuthContext: Calling login API...');
      const response = await authAPI.login({ email, password });
      console.log('AuthContext: Login API response:', response);
      
      if (response.success) {
        const { user: userData, token: userToken } = response.data;
        setUser(userData);
        setToken(userToken);
        localStorage.setItem('auth_token', userToken);
        localStorage.setItem('user', JSON.stringify(userData));
        console.log('AuthContext: Login successful, user saved');
      } else {
        console.error('AuthContext: Login failed -', response.message);
        throw new Error(response.message || 'Login failed');
      }
    } catch (error) {
      console.error('AuthContext: Login error caught:', error);
      const errorMessage = error.response?.data?.message || error.message || 'Login failed. Please try again.';
      throw new Error(errorMessage);
    }
  };

  const register = async (userData) => {
    try {
      const response = await authAPI.register(userData);
      if (response.success) {
        const { user: newUser, token: userToken } = response.data;
        setUser(newUser);
        setToken(userToken);
        localStorage.setItem('auth_token', userToken);
        localStorage.setItem('user', JSON.stringify(newUser));
      } else {
        throw new Error(response.message || 'Registration failed');
      }
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Registration failed');
    }
  };

  const logout = () => {
    setUser(null);
    setToken(null);
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    
    // Call API logout (don't wait for response)
    authAPI.logout().catch(() => {
      // Ignore errors on logout
    });
  };

  const value = {
    user,
    token,
    login,
    register,
    logout,
    loading,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};
