import { useState, useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import { authAPI } from '../services/api';
import { useAuth } from '../contexts/AuthContext';
import { showSuccess, showToast } from '../utils/sweetAlert';

const VerifyOtp = () => {
  const [otp, setOtp] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [resending, setResending] = useState(false);
  const navigate = useNavigate();
  const location = useLocation();
  const email = location.state?.email;
  const password = location.state?.password;
  const { login } = useAuth();

  useEffect(() => {
    if (!email) {
      navigate('/register');
    }
  }, [email, navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setSuccess('');

    if (otp.length !== 6) {
      setError('OTP must be 6 digits');
      setLoading(false);
      return;
    }

    try {
      const response = await authAPI.verifyOtp({ email, otp_code: otp });
      if (response.success) {
        setSuccess('Email verified successfully!');
        
        // If we have the password, auto-login the user
        if (password) {
          try {
            await login(email, password);
            showSuccess('Welcome!', 'Your account has been verified and you are now logged in.');
            setTimeout(() => {
              navigate('/dashboard');
            }, 1500);
          } catch (loginErr) {
            // If auto-login fails, redirect to login page
            showToast('success', 'Email verified! Please login.');
            setTimeout(() => {
              navigate('/login');
            }, 2000);
          }
        } else {
          // No password available, redirect to login
          showToast('success', 'Email verified! Please login.');
          setTimeout(() => {
            navigate('/login');
          }, 2000);
        }
      } else {
        setError(response.message || 'Verification failed');
      }
    } catch (err) {
      setError(err.response?.data?.message || 'Verification failed. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  const handleResend = async () => {
    setResending(true);
    setError('');
    setSuccess('');

    try {
      const response = await authAPI.resendVerification(email);
      if (response.success) {
        setSuccess('New OTP code sent to your email!');
        showToast('success', 'New OTP sent!');
      } else {
        setError(response.message || 'Failed to resend OTP');
      }
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to resend OTP');
    } finally {
      setResending(false);
    }
  };

  const handleOtpChange = (e) => {
    const value = e.target.value.replace(/\D/g, '').slice(0, 6);
    setOtp(value);
    if (error) setError('');
  };

  return (
    <div className="max-w-md mx-auto glass-effect rounded-xl shadow-soft p-8 animate-fade-in">
      <div className="text-center mb-8">
        <div className="mx-auto w-16 h-16 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
          <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
        </div>
        <h2 className="text-3xl font-bold gradient-text mb-2">
          Verify Your Email
        </h2>
        <p className="text-gray-600">
          We've sent a 6-digit code to
        </p>
        <p className="text-gray-900 font-semibold mt-1">{email}</p>
      </div>

      {error && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {error}
        </div>
      )}

      {success && (
        <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          {success}
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-6">
        <div>
          <label htmlFor="otp" className="block text-sm font-medium text-gray-700 mb-2">
            Enter OTP Code
          </label>
          <input
            type="text"
            id="otp"
            value={otp}
            onChange={handleOtpChange}
            maxLength={6}
            required
            className="input-field text-center text-2xl font-bold tracking-widest"
            placeholder="000000"
            autoComplete="off"
          />
          <p className="mt-2 text-sm text-gray-500 text-center">
            {otp.length}/6 digits
          </p>
        </div>

        <button
          type="submit"
          disabled={loading || otp.length !== 6}
          className="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {loading ? 'Verifying...' : 'Verify Email'}
        </button>
      </form>

      <div className="mt-6 text-center">
        <p className="text-gray-600 text-sm mb-2">
          Didn't receive the code?
        </p>
        <button
          onClick={handleResend}
          disabled={resending}
          className="gradient-text font-semibold text-sm disabled:opacity-50 hover:opacity-80 transition-opacity"
        >
          {resending ? 'Sending...' : 'Resend OTP'}
        </button>
      </div>

      <div className="mt-6 text-center">
        <button
          onClick={() => navigate('/register')}
          className="text-gray-600 hover:text-gray-800 text-sm transition-colors"
        >
          ← Back to Registration
        </button>
      </div>
    </div>
  );
};

export default VerifyOtp;
