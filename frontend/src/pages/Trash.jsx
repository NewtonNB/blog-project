import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { postsAPI } from '../services/api';
import LoadingSpinner from '../components/LoadingSpinner';
import { showConfirm, showSuccess, showError } from '../utils/sweetAlert';
import { FiTrash2, FiRotateCcw, FiXCircle, FiArrowLeft } from 'react-icons/fi';

const Trash = () => {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchTrashedPosts();
  }, []);

  const fetchTrashedPosts = async () => {
    try {
      setLoading(true);
      const response = await postsAPI.getTrashed();
      if (response.success) {
        setPosts(response.data);
      }
    } catch (error) {
      console.error('Error fetching trashed posts:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleRestore = async (slug) => {
    const result = await showConfirm(
      'Restore Post?',
      'This post will be restored to your dashboard.',
      'Yes, restore it!'
    );

    if (result.isConfirmed) {
      try {
        await postsAPI.restore(slug);
        setPosts(posts.filter(post => post.slug !== slug));
        showSuccess('Restored!', 'Post has been restored successfully.');
      } catch (error) {
        showError('Failed!', 'Failed to restore post. Please try again.');
      }
    }
  };

  const handlePermanentDelete = async (slug) => {
    const result = await showConfirm(
      'Permanently Delete?',
      'This action cannot be undone! The post will be deleted forever.',
      'Yes, delete forever!'
    );

    if (result.isConfirmed) {
      try {
        await postsAPI.forceDelete(slug);
        setPosts(posts.filter(post => post.slug !== slug));
        showSuccess('Deleted!', 'Post has been permanently deleted.');
      } catch (error) {
        showError('Failed!', 'Failed to permanently delete post. Please try again.');
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
          <div className="flex items-center gap-3 mb-2">
            <FiTrash2 className="w-8 h-8 text-red-600" />
            <h1 className="text-3xl sm:text-4xl font-bold gradient-text">Trash</h1>
          </div>
          <p className="text-gray-600 mt-2">Deleted posts (can be restored or permanently deleted)</p>
        </div>
        <Link
          to="/dashboard"
          className="gradient-text font-semibold hover:opacity-80 transition-opacity flex items-center gap-2"
        >
          <FiArrowLeft className="w-4 h-4" />
          Back to Dashboard
        </Link>
      </section>

      {/* Divider */}
      <div className="relative mb-8">
        <div className="absolute inset-0 flex items-center">
          <div className="w-full border-t-2 border-gray-200"></div>
        </div>
        <div className="relative flex justify-center">
          <span className="bg-white px-4 text-sm text-gray-500 font-medium">
            Deleted Posts
          </span>
        </div>
      </div>

      {/* Posts List Section */}
      <section>
      {posts.length > 0 ? (
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
                    Deleted Date
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {posts.map((post) => (
                  <tr key={post.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4">
                      <div>
                        <p className="text-sm font-medium text-gray-900">
                          {post.title}
                        </p>
                        {post.excerpt && (
                          <p className="text-sm text-gray-500 mt-1 truncate max-w-xs">
                            {post.excerpt}
                          </p>
                        )}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className="text-sm text-gray-900">{post.category?.name}</span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {post.deleted_at ? formatDate(post.deleted_at) : 'N/A'}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div className="flex space-x-3">
                        <button
                          onClick={() => handleRestore(post.slug)}
                          className="text-green-600 hover:text-green-800 font-medium transition-colors flex items-center gap-1"
                        >
                          <FiRotateCcw className="w-4 h-4" />
                          Restore
                        </button>
                        <button
                          onClick={() => handlePermanentDelete(post.slug)}
                          className="text-red-600 hover:text-red-800 font-medium transition-colors flex items-center gap-1"
                        >
                          <FiXCircle className="w-4 h-4" />
                          Delete Forever
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      ) : (
        <div className="text-center py-12 glass-effect rounded-xl shadow-soft">
          <FiTrash2 className="mx-auto h-16 w-16 text-gray-400 mb-4" />
          <p className="text-gray-500 text-lg mb-4">Trash is empty.</p>
          <Link
            to="/dashboard"
            className="gradient-text font-semibold hover:opacity-80 transition-opacity flex items-center justify-center gap-2"
          >
            <FiArrowLeft className="w-4 h-4" />
            Go to Dashboard
          </Link>
        </div>
      )}
      </section>
    </div>
  );
};

export default Trash;
