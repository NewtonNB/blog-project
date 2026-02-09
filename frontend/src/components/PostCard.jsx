import React from 'react';
import { Link } from 'react-router-dom';
import { FiMessageCircle } from 'react-icons/fi';

const PostCard = ({ post }) => {
  // Safety check - return null if post is undefined
  if (!post) {
    return null;
  }

  const formatDate = (dateString) => {
    if (!dateString) return 'No date';
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  };

  return (
    <article className="bg-white rounded-lg shadow-soft overflow-hidden card-hover animate-fade-in">
      {post.featured_image && (
        <img
          src={post.featured_image}
          alt={post.title || 'Post image'}
          className="w-full h-48 object-cover transition-transform duration-300 hover:scale-105"
        />
      )}
      
      <div className="p-6">
        <div className="flex items-center text-sm text-gray-500 mb-2">
          {post.category && (
            <>
              <Link
                to={`/category/${post.category.slug}`}
                className="text-primary-600 hover:text-primary-700 font-medium"
              >
                {post.category.name}
              </Link>
              <span className="mx-2">•</span>
            </>
          )}
          <span>{formatDate(post.published_at || post.created_at)}</span>
        </div>

        <h2 className="text-xl font-bold text-gray-900 mb-3 hover:text-primary-600 transition-colors">
          <Link to={`/post/${post.slug}`}>
            {post.title || 'Untitled Post'}
          </Link>
        </h2>

        {post.excerpt && (
          <p className="text-gray-600 mb-4 line-clamp-3">
            {post.excerpt}
          </p>
        )}

        <div className="flex items-center justify-between">
          <div className="flex items-center gap-4">
            <div className="flex items-center">
              {post.user?.avatar && (
                <img
                  src={post.user.avatar}
                  alt={post.user.name || 'User'}
                  className="w-8 h-8 rounded-full mr-2"
                />
              )}
              <span className="text-sm text-gray-700">
                By {post.user?.name || 'Anonymous'}
              </span>
            </div>
            
            {post.comments_count !== undefined && (
              <div className="flex items-center gap-1 text-sm text-gray-500">
                <FiMessageCircle className="w-4 h-4" />
                <span>{post.comments_count}</span>
              </div>
            )}
          </div>

          <Link
            to={`/post/${post.slug}`}
            className="text-primary-600 hover:text-primary-700 font-medium text-sm"
          >
            Read more →
          </Link>
        </div>
      </div>
    </article>
  );
};

export default PostCard;