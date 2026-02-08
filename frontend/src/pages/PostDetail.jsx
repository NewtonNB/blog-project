import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import { postsAPI } from '../services/api';
import { useAuth } from '../contexts/AuthContext';
import LoadingSpinner from '../components/LoadingSpinner';

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
    if (!post || !window.confirm('Are you sure you want to delete this post?')) {
      return;
    }

    try {
      await postsAPI.delete(post.slug);
      navigate('/dashboard');
    } catch (err) {
      alert('Failed to delete post');
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
    <article className="max-w-4xl mx-auto">
      {/* Header */}
      <header className="mb-8">
        <div className="flex items-center text-sm text-gray-500 mb-4">
          <Link
            to={`/category/${post.category.slug}`}
            className="text-primary-600 hover:text-primary-700 font-medium"
          >
            {post.category.name}
          </Link>
          <span className="mx-2">•</span>
          <span>{formatDate(post.published_at || post.created_at)}</span>
          {post.status === 'draft' && (
            <>
              <span className="mx-2">•</span>
              <span className="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">
                Draft
              </span>
            </>
          )}
        </div>

        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
          {post.title}
        </h1>

        {post.excerpt && (
          <p className="text-xl text-gray-600 mb-6">
            {post.excerpt}
          </p>
        )}

        <div className="flex items-center justify-between">
          <div className="flex items-center">
            {post.user.avatar && (
              <img
                src={post.user.avatar}
                alt={post.user.name}
                className="w-12 h-12 rounded-full mr-4"
              />
            )}
            <div>
              <p className="font-medium text-gray-900">{post.user.name}</p>
              {post.user.bio && (
                <p className="text-sm text-gray-600">{post.user.bio}</p>
              )}
            </div>
          </div>

          {canEdit && (
            <div className="flex space-x-2">
              <Link
                to={`/edit-post/${post.slug}`}
                className="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors"
              >
                Edit
              </Link>
              <button
                onClick={handleDelete}
                className="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors"
              >
                Delete
              </button>
            </div>
          )}
        </div>
      </header>

      {/* Featured Image */}
      {post.featured_image && (
        <div className="mb-8">
          <img
            src={post.featured_image}
            alt={post.title}
            className="w-full h-64 md:h-96 object-cover rounded-lg"
          />
        </div>
      )}

      {/* Content */}
      <div className="prose prose-lg max-w-none mb-8">
        <div
          dangerouslySetInnerHTML={{ __html: post.content.replace(/\n/g, '<br>') }}
        />
      </div>

      {/* Footer */}
      <footer className="border-t pt-8">
        <div className="flex justify-between items-center">
          <Link
            to="/"
            className="text-primary-600 hover:text-primary-700 font-medium"
          >
            ← Back to Home
          </Link>
          
          <div className="text-sm text-gray-500">
            Last updated: {formatDate(post.updated_at)}
          </div>
        </div>
      </footer>
    </article>
  );
};

export default PostDetail;
