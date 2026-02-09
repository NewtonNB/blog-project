import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { categoriesAPI } from '../services/api';
import LoadingSpinner from '../components/LoadingSpinner';
import { FiFolder, FiArrowRight } from 'react-icons/fi';

const Categories = () => {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchCategories();
  }, []);

  const fetchCategories = async () => {
    try {
      setLoading(true);
      const response = await categoriesAPI.getAll();
      if (response.success) {
        setCategories(response.data);
      } else {
        setError('Failed to load categories');
      }
    } catch (err) {
      setError('Failed to load categories');
      console.error('Error fetching categories:', err);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <LoadingSpinner size="lg" className="py-12" />;
  }

  if (error) {
    return (
      <div className="text-center py-12">
        <p className="text-red-600 mb-4">{error}</p>
        <button
          onClick={fetchCategories}
          className="text-primary-600 hover:text-primary-700 font-medium"
        >
          Try Again
        </button>
      </div>
    );
  }

  return (
    <div className="max-w-6xl mx-auto px-4 py-8">
      {/* Header Section */}
      <section className="mb-8 animate-fade-in">
        <h1 className="text-4xl sm:text-5xl font-bold gradient-text mb-2">Categories</h1>
        <p className="text-gray-600">Browse posts by category</p>
      </section>

      {/* Divider */}
      <div className="relative mb-8">
        <div className="absolute inset-0 flex items-center">
          <div className="w-full border-t-2 border-gray-200"></div>
        </div>
        <div className="relative flex justify-center">
          <span className="bg-white px-4 text-sm text-gray-500 font-medium">
            All Categories
          </span>
        </div>
      </div>

      {/* Categories Grid Section */}
      <section>
      {categories.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-slide-up">
          {categories.map((category) => (
            <Link
              key={category.id}
              to={`/category/${category.slug}`}
              className="glass-effect rounded-xl shadow-soft p-6 card-hover"
            >
              <div className="flex items-center justify-between mb-4">
                <div className="flex items-center gap-3">
                  <FiFolder className="w-8 h-8 text-blue-600" />
                  <h2 className="text-2xl font-bold gradient-text">
                    {category.name}
                  </h2>
                </div>
                {category.posts_count !== undefined && (
                  <span className="bg-gradient-to-r from-blue-100 to-purple-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                    {category.posts_count} {category.posts_count === 1 ? 'post' : 'posts'}
                  </span>
                )}
              </div>
              
              {category.description && (
                <p className="text-gray-600 line-clamp-3">
                  {category.description}
                </p>
              )}
              
              <div className="mt-4 gradient-text font-semibold flex items-center hover:opacity-80 transition-opacity">
                View Posts
                <FiArrowRight className="w-4 h-4 ml-1" />
              </div>
            </Link>
          ))}
        </div>
      ) : (
        <div className="text-center py-12 glass-effect rounded-xl shadow-soft">
          <p className="text-gray-500 text-lg">No categories found.</p>
        </div>
      )}
      </section>
    </div>
  );
};

export default Categories;
