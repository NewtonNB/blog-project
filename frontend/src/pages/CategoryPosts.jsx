import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { categoriesAPI } from '../services/api';
import PostCard from '../components/PostCard';
import LoadingSpinner from '../components/LoadingSpinner';

const CategoryPosts = () => {
  const { slug } = useParams();
  const [category, setCategory] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    if (slug) {
      fetchCategory();
    }
  }, [slug]);

  const fetchCategory = async () => {
    try {
      setLoading(true);
      const response = await categoriesAPI.getBySlug(slug);
      if (response.success) {
        setCategory(response.data);
      } else {
        setError('Category not found');
      }
    } catch (err) {
      setError('Failed to load category');
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <LoadingSpinner size="lg" className="py-12" />;
  }

  if (error || !category) {
    return (
      <div className="text-center py-12">
        <h2 className="text-2xl font-bold text-gray-900 mb-4">Category Not Found</h2>
        <p className="text-gray-600 mb-6">{error || 'The category you are looking for does not exist.'}</p>
        <Link to="/" className="text-primary-600 hover:text-primary-700">
          ← Back to Home
        </Link>
      </div>
    );
  }

  return (
    <div className="max-w-7xl mx-auto">
      {/* Category Header */}
      <div className="text-center mb-12">
        <nav className="mb-4">
          <Link to="/" className="text-primary-600 hover:text-primary-700">
            Home
          </Link>
          <span className="mx-2 text-gray-500">→</span>
          <span className="text-gray-900">{category.name}</span>
        </nav>
        
        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
          {category.name}
        </h1>
        
        {category.description && (
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            {category.description}
          </p>
        )}
        
        <p className="text-gray-500 mt-4">
          {category.posts.length} {category.posts.length === 1 ? 'post' : 'posts'}
        </p>
      </div>

      {/* Posts Grid */}
      {category.posts.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {category.posts.map((post) => (
            <PostCard key={post.id} post={post} />
          ))}
        </div>
      ) : (
        <div className="text-center py-12">
          <p className="text-gray-500 text-lg mb-4">
            No posts found in this category yet.
          </p>
          <Link to="/" className="text-primary-600 hover:text-primary-700">
            ← Browse all posts
          </Link>
        </div>
      )}
    </div>
  );
};

export default CategoryPosts;
