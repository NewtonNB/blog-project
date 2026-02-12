import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { postsAPI } from '../services/api';
import { useAuth } from '../contexts/AuthContext';
import LoadingSpinner from '../components/LoadingSpinner';
import PaginationNew from '../components/PaginationNew';
import { showConfirm, showSuccess, showError } from '../utils/sweetAlert';
import { FiPlus, FiFileText, FiCheckCircle, FiEdit, FiEye, FiTrash2 } from 'react-icons/fi';
import { HiOutlineDocumentText } from 'react-icons/hi';

const Dashboard = () => {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState('all');
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const { user } = useAuth();

  useEffect(() => {
    fetchUserPosts();
  }, [filter, currentPage]);

  const fetchUserPosts = async () => {
    try {
      setLoading(true);
      const response = await postsAPI.getAll({
        status: filter === 'all' ? undefined : filter,
        per_page: 10,
        page: currentPage,
      });

      if (response.success) {
        // Filter posts by current user
        const allPosts = response.data.data || response.data;
        const userPosts = Array.isArray(allPosts) 
          ? allPosts.filter(post => post.user?.id === user?.id)
          : [];
        
        setPosts(userPosts);
        
        // Calculate total pages based on filtered results
        const meta = response.data.meta;
        setTotalPages(meta?.total_pages || 1);
      }
    } catch (error) {
      console.error('Error fetching posts:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (slug) => {
    const result = await showConfirm(
      'Move to Trash?',
      'This post will be moved to trash. You can restore it later.',
      'Yes, move it!'
    );

    if (result.isConfirmed) {
      try {
        await postsAPI.delete(slug);
        setPosts(posts.filter(post => post.slug !== slug));
        showSuccess('Moved to Trash!', 'Post has been moved to trash successfully.');
      } catch (error) {
        showError('Failed!', 'Failed to delete post. Please try again.');
      }
    }
  };

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    });
  };

  if (loading) {
    return <LoadingSpinner size="lg" className="py-12" />;
  }

  return (
    <div className="max-w-6xl mx-auto px-4 py-8">
      {/* Header Section */}
      <section className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 animate-fade-in">
        <div>
          <h1 className="text-3xl sm:text-4xl font-bold gradient-text">Dashboard</h1>
          <p className="text-gray-600 mt-2">Manage your blog posts</p>
        </div>
        <Link
          to="/create-post"
          className="btn-primary flex items-center gap-2"
        >
          <FiPlus className="w-5 h-5" />
          Create New Post
        </Link>
      </section>

      {/* Stats Section */}
      <section className="mb-8">
        <h2 className="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
          <FiFileText className="w-5 h-5 text-blue-600" />
          Overview
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 animate-slide-up">
          <div className="glass-effect p-6 rounded-xl shadow-soft card-hover">
            <div className="flex items-center justify-between mb-2">
              <h3 className="text-lg font-medium text-gray-700">Total Posts</h3>
              <FiFileText className="w-8 h-8 text-blue-600" />
            </div>
            <p className="text-4xl font-bold gradient-text">{posts.length}</p>
          </div>
          <div className="glass-effect p-6 rounded-xl shadow-soft card-hover">
            <div className="flex items-center justify-between mb-2">
              <h3 className="text-lg font-medium text-gray-700">Published</h3>
              <FiCheckCircle className="w-8 h-8 text-green-600" />
            </div>
            <p className="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
              {posts.filter(post => post.status === 'published').length}
            </p>
          </div>
          <div className="glass-effect p-6 rounded-xl shadow-soft card-hover">
            <div className="flex items-center justify-between mb-2">
              <h3 className="text-lg font-medium text-gray-700">Drafts</h3>
              <HiOutlineDocumentText className="w-8 h-8 text-yellow-600" />
            </div>
            <p className="text-4xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">
              {posts.filter(post => post.status === 'draft').length}
            </p>
          </div>
        </div>
      </section>

      {/* Divider */}
      <div className="relative mb-8">
        <div className="absolute inset-0 flex items-center">
          <div className="w-full border-t-2 border-gray-200"></div>
        </div>
        <div className="relative flex justify-center">
          <span className="bg-white px-4 text-sm text-gray-500 font-medium">
            Your Posts
          </span>
        </div>
      </div>

      {/* Filter Section */}
      <section className="mb-6">
        <div className="flex space-x-2">
          {['all', 'published', 'draft'].map((status) => (
            <button
              key={status}
              onClick={() => setFilter(status)}
              className={`px-4 py-2 rounded-lg font-medium capitalize transition-all duration-300 ${
                filter === status
                  ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
                  : 'btn-secondary'
              }`}
            >
              {status}
            </button>
          ))}
        </div>
      </section>

      {/* Posts List Section */}
      <section>
      {posts.length > 0 ? (
        <>
          <div className="glass-effect rounded-xl shadow-soft overflow-hidden">
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Title
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Category
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {posts.map((post) => (
                  <tr key={post.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div>
                        <Link
                          to={`/post/${post.slug}`}
                          className="text-sm font-medium text-gray-900 hover:text-transparent hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 hover:bg-clip-text transition-all"
                        >
                          {post.title}
                        </Link>
                        {post.excerpt && (
                          <p className="text-sm text-gray-500 mt-1 truncate max-w-xs">
                            {post.excerpt}
                          </p>
                        )}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className="text-sm text-gray-900">{post.category.name}</span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span
                        className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                          post.status === 'published'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-yellow-100 text-yellow-800'
                        }`}
                      >
                        {post.status}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {formatDate(post.published_at || post.created_at)}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div className="flex space-x-3">
                        <Link
                          to={`/post/${post.slug}`}
                          className="text-blue-600 hover:text-blue-800 font-medium transition-colors flex items-center gap-1"
                        >
                          <FiEye className="w-4 h-4" />
                          View
                        </Link>
                        <Link
                          to={`/edit-post/${post.slug}`}
                          className="text-purple-600 hover:text-purple-800 font-medium transition-colors flex items-center gap-1"
                        >
                          <FiEdit className="w-4 h-4" />
                          Edit
                        </Link>
                        <button
                          onClick={() => handleDelete(post.slug)}
                          className="text-red-600 hover:text-red-800 font-medium transition-colors flex items-center gap-1"
                        >
                          <FiTrash2 className="w-4 h-4" />
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Pagination */}
        <PaginationNew
          currentPage={currentPage}
          totalPages={totalPages}
          onPageChange={setCurrentPage}
        />
        </>
      ) : (
        <div className="text-center py-12 glass-effect rounded-xl shadow-soft">
          <p className="text-gray-500 text-lg mb-4">No posts found.</p>
          <Link
            to="/create-post"
            className="gradient-text font-semibold hover:opacity-80 transition-opacity"
          >
            Create your first post →
          </Link>
        </div>
      )}
      </section>
    </div>
  );
};

export default Dashboard;
