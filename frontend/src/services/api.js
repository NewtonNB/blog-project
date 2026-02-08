import axios from 'axios';

// API Configuration
const API_BASE_URL = 'http://localhost:8000/api';

// Create axios instance with default config
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add auth token to requests if available
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Handle response errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth API
export const authAPI = {
  register: async (userData) => {
    const response = await api.post('/auth/register', userData);
    return response.data;
  },

  login: async (credentials) => {
    const response = await api.post('/auth/login', credentials);
    return response.data;
  },

  logout: async () => {
    const response = await api.post('/auth/logout');
    return response.data;
  },

  getProfile: async () => {
    const response = await api.get('/auth/profile');
    return response.data;
  },

  verifyOtp: async (otpData) => {
    const response = await api.post('/auth/verify-otp', otpData);
    return response.data;
  },

  resendVerification: async (email) => {
    const response = await api.post('/auth/resend-verification', { email });
    return response.data;
  },
};

// Posts API
export const postsAPI = {
  getAll: async (params) => {
    const response = await api.get('/posts', { params });
    return response.data;
  },

  getBySlug: async (slug) => {
    const response = await api.get(`/posts/${slug}`);
    return response.data;
  },

  create: async (postData) => {
    const response = await api.post('/posts', postData);
    return response.data;
  },

  update: async (slug, postData) => {
    const response = await api.put(`/posts/${slug}`, postData);
    return response.data;
  },

  delete: async (slug) => {
    const response = await api.delete(`/posts/${slug}`);
    return response.data;
  },
};

// Categories API
export const categoriesAPI = {
  getAll: async () => {
    const response = await api.get('/categories');
    return response.data;
  },

  getBySlug: async (slug) => {
    const response = await api.get(`/categories/${slug}`);
    return response.data;
  },

  create: async (categoryData) => {
    const response = await api.post('/categories', categoryData);
    return response.data;
  },

  update: async (slug, categoryData) => {
    const response = await api.put(`/categories/${slug}`, categoryData);
    return response.data;
  },

  delete: async (slug) => {
    const response = await api.delete(`/categories/${slug}`);
    return response.data;
  },
};

export default api;
