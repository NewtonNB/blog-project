import { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import { postsAPI } from '../services/api';
import { useAuth } from '../contexts/AuthContext';
import LoadingSpinner from '../components/LoadingSpinner';
import Comments from '../components/Comments';
import { showConfirm, showSuccess, showError } from '../utils/sweetAlert';
import { FiEdit, FiTrash2, FiArrowLeft, FiCalendar, FiUser, FiTag } from 'react-icons/fi';

const PostDetail = () => {
  const { slug } = useParams();
  const [post, setPost] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const { user } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (slug) {
      fetchPost();
    }
  }, [slug]);

  const fetchPost = async () => {
    try {
      setLoading(true);
      const response = await postsAPI.getBySlug(slug);
      if (response.success) {
        setPost(response.data);
      } else {
        setError('Post not found');
      }
    } catch (err) {
      setError('Failed to load post');
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async () => {
    if (!post) return;

    const result = await showConfirm(
      'Move to Trash?',
      'This post will be moved to trash. You can restore it later.',
      'Yes, move it!'
    );

    if (result.isConfirmed) {
      try {
        await postsAPI.delete(post.slug);
        showSuccess('Moved to Trash!', 'Post has been moved to trash successfully.');
        setTimeout(() => {
          navigate('/dashboard');
        }, 1500);
      } catch (err) {
        showError('Failed!', 'Failed to delete post. Please try again.');
      }
    }
  };

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  };

  if (loading) {
    return <LoadingSpinner size="lg" className="py-12" />;
  }

  if (error || !post) {
    return (
      <div className="text-center py-12">
        <h2 className="text-2xl font-bold text-gray-900 mb-4">Post Not Found</h2>
        <p className="text-gray-600 mb-6">{error || 'The post you are looking for does not exist.'}</p>
        <Link to="/" className="text-primary-600 hover:text-primary-700">
          ← Back to Home
        </Link>
      </div>
    );
  }

  const canEdit = user && user.id === post.user.id;

  return (
    <article className="max-w-4xl mx-auto px-4 py-8">
      {/* Header */}
      <header className="mb-8 animate-fade-in">
        <div className="flex items-center text-sm text-gray-500 mb-4 flex-wrap gap-2">
          <Link
            to={`/category/${post.category.slug}`}
            className="gradient-text font-semibold hover:opacity-80 transition-opacity flex items-center gap-1"
          >
            <FiTag className="w-4 h-4" />
            {post.category.name}
          </Link>
          <span className="mx-2">•</span>
          <span className="flex items-center gap-1">
            <FiCalendar className="w-4 h-4" />
            {formatDate(post.published_at || post.created_at)}
          </span>
          {post.status === 'draft' && (
            <>
              <span className="mx-2">•</span>
              <span className="bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                Draft
              </span>
            </>
          )}
        </div>

        <h1 className="text-3xl sm:text-4xl md:text-5xl font-bold gradient-text mb-6">
          {post.title}
        </h1>

        {post.excerpt && (
          <p className="text-xl text-gray-600 mb-6">
            {post.excerpt}
          </p>
        )}

        <div className="flex items-center justify-between flex-wrap gap-4">
          <div className="flex items-center">
            {post.user.avatar ? (
              <img
                src={post.user.avatar}
                alt={post.user.name}
                className="w-12 h-12 rounded-full mr-4"
              />
            ) : (
              <div className="w-12 h-12 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 flex items-center justify-center mr-4">
                <FiUser className="w-6 h-6 text-white" />
              </div>
            )}
            <div>
              <p className="font-medium text-gray-900">{post.user.name}</p>
              {post.user.bio && (
                <p className="text-sm text-gray-600">{post.user.bio}</p>
              )}
            </div>
          </div>

          {canEdit && (
            <div className="flex space-x-3">
              <Link
                to={`/edit-post/${post.slug}`}
                className="btn-primary flex items-center gap-2"
              >
                <FiEdit className="w-4 h-4" />
                Edit
              </Link>
              <button
                onClick={handleDelete}
                className="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center gap-2"
              >
                <FiTrash2 className="w-4 h-4" />
                Delete
              </button>
            </div>
          )}
        </div>
      </header>

      {/* Featured Image */}
      {post.featured_image && (
        <div className="mb-8 animate-slide-up">
          <img
            src={post.featured_image}
            alt={post.title}
            className="w-full h-64 md:h-96 object-cover rounded-xl shadow-soft"
          />
        </div>
      )}

      {/* Content */}
      <div className="prose prose-lg max-w-none mb-8 glass-effect p-6 sm:p-8 rounded-xl shadow-soft">
        <div
          dangerouslySetInnerHTML={{ __html: post.content.replace(/\n/g, '<br>') }}
        />
      </div>

      {/* Footer */}
      <footer className="border-t pt-8 mb-8">
        <div className="flex flex-col sm:flex-row justify-between items-center gap-4">
          <Link
            to="/"
            className="gradient-text font-semibold hover:opacity-80 transition-opacity flex items-center gap-2"
          >
            <FiArrowLeft className="w-4 h-4" />
            Back to Home
          </Link>
          
          <div className="text-sm text-gray-500 flex items-center gap-1">
            <FiCalendar className="w-4 h-4" />
            Last updated: {formatDate(post.updated_at)}
          </div>
        </div>
      </footer>

      {/* Comments Section */}
      <Comments postSlug={post.slug} post={post} />
    </article>
  );
};

export default PostDetail;
