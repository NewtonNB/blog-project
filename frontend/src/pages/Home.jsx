import React, { useState, useEffect } from 'react';
import { postsAPI, categoriesAPI } from '../services/api';
import PostCard from '../components/PostCard';
import LoadingSpinner from '../components/LoadingSpinner';
import Pagination from '../components/Pagination';
import { FiSearch } from 'react-icons/fi';

const Home = () => {
  const [posts, setPosts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);

  useEffect(() => {
    fetchPosts();
    fetchCategories();
  }, [currentPage, selectedCategory, searchTerm]);

  const fetchPosts = async () => {
    try {
      setLoading(true);
      const params = {
        status: 'published',
        page: currentPage,
        per_page: 9,
      };

      // Only add category if one is selected
      if (selectedCategory) {
        params.category = selectedCategory;
      }

      // Only add search if there's a search term
      if (searchTerm) {
        params.search = searchTerm;
      }

      const response = await postsAPI.getAll(params);

      console.log('Full API Response:', response);

      if (response.success) {
        // Handle different response structures
        let postsData = [];
        let totalPagesCount = 1;

        if (response.data) {
          // Check if data has nested structure (PostCollection)
          if (response.data.data) {
            postsData = response.data.data;
            totalPagesCount = response.data.meta?.total_pages || response.data.last_page || 1;
          } 
          // Check if data is directly an array
          else if (Array.isArray(response.data)) {
            postsData = response.data;
          }
          // Check if data has collection property
          else if (response.data.collection) {
            postsData = response.data.collection;
          }
        }

        console.log('Extracted posts:', postsData);
        console.log('Total pages:', totalPagesCount);

        setPosts(Array.isArray(postsData) ? postsData : []);
        setTotalPages(totalPagesCount);
      }
    } catch (error) {
      console.error('Error fetching posts:', error);
      setPosts([]);
    } finally {
      setLoading(false);
    }
  };

  const fetchCategories = async () => {
    try {
      const response = await categoriesAPI.getAll();
      if (response.success) {
        setCategories(response.data);
      }
    } catch (error) {
      console.error('Error fetching categories:', error);
    }
  };

  const handleSearch = (e) => {
    e.preventDefault();
    setCurrentPage(1);
    fetchPosts();
  };

  const handleCategoryChange = (categorySlug) => {
    setSelectedCategory(categorySlug);
    setCurrentPage(1);
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      {/* Hero Section */}
      <section className="text-center mb-8 md:mb-12 animate-fade-in">
        <h1 className="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold gradient-text mb-3 md:mb-4">
          Welcome to Our Blog
        </h1>
        <p className="text-base sm:text-lg md:text-xl text-gray-600 max-w-2xl mx-auto px-4">
          Discover amazing stories, insights, and knowledge from our community of writers.
        </p>
      </section>

      {/* Divider */}
      <div className="relative mb-8">
        <div className="absolute inset-0 flex items-center">
          <div className="w-full border-t-2 border-gradient-to-r from-blue-200 via-purple-200 to-blue-200"></div>
        </div>
        <div className="relative flex justify-center">
          <span className="bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 px-4 text-sm text-gray-500 font-medium">
            Search & Filter
          </span>
        </div>
      </div>

      {/* Search and Filter Section */}
      <section className="mb-8 md:mb-12 space-y-4 animate-slide-up">
        {/* Search Form */}
        <form onSubmit={handleSearch} className="flex gap-2 w-full">
          <div className="relative flex-1">
            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <FiSearch className="h-5 w-5 text-gray-400" />
            </div>
            <input
              type="text"
              placeholder="Search posts..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="input-field pl-12 text-sm sm:text-base"
            />
          </div>
          <button
            type="submit"
            className="btn-primary px-4 sm:px-6 py-2 text-sm sm:text-base whitespace-nowrap"
          >
            Search
          </button>
        </form>

        {/* Category Filter */}
        <div className="flex gap-2 flex-wrap justify-center sm:justify-start">
          <button
            onClick={() => handleCategoryChange('')}
            className={`px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 ${
              selectedCategory === ''
                ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
                : 'bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200 hover:border-blue-500'
            }`}
          >
            All
          </button>
          {categories.map((category) => (
            <button
              key={category.id}
              onClick={() => handleCategoryChange(category.slug)}
              className={`px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 ${
                selectedCategory === category.slug
                  ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
                  : 'bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200 hover:border-blue-500'
              }`}
            >
              {category.name}
            </button>
          ))}
        </div>
      </section>

      {/* Divider */}
      <div className="relative mb-8">
        <div className="absolute inset-0 flex items-center">
          <div className="w-full border-t-2 border-gradient-to-r from-blue-200 via-purple-200 to-blue-200"></div>
        </div>
        <div className="relative flex justify-center">
          <span className="bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 px-4 text-sm text-gray-500 font-medium">
            Latest Posts
          </span>
        </div>
      </div>

      {/* Posts Grid Section */}
      <section>
      {loading ? (
        <LoadingSpinner size="lg" className="py-12" />
      ) : posts.length > 0 ? (
        <>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 mb-6 md:mb-8">
            {posts.map((post) => (
              <PostCard key={post.id} post={post} />
            ))}
          </div>

          {/* Pagination */}
          <Pagination
            currentPage={currentPage}
            totalPages={totalPages}
            onPageChange={setCurrentPage}
          />
        </>
      ) : (
        <div className="text-center py-12 px-4">
          <p className="text-gray-500 text-base sm:text-lg">No posts found.</p>
          {searchTerm && (
            <button
              onClick={() => {
                setSearchTerm('');
                setSelectedCategory('');
                setCurrentPage(1);
              }}
              className="mt-4 text-sm sm:text-base text-primary-600 hover:text-primary-700"
            >
              Clear search and filters
            </button>
          )}
        </div>
      )}
      </section>
    </div>
  );
};

export default Home;
