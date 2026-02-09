import { useState, useEffect } from 'react';
import { commentsAPI } from '../services/api';
import { useAuth } from '../contexts/AuthContext';
import { showSuccess, showError, showConfirm } from '../utils/sweetAlert';
import { FiSend, FiEdit, FiTrash2, FiUser } from 'react-icons/fi';

const Comments = ({ postSlug }) => {
  const [comments, setComments] = useState([]);
  const [loading, setLoading] = useState(true);
  const [newComment, setNewComment] = useState('');
  const [editingId, setEditingId] = useState(null);
  const [editContent, setEditContent] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const { user } = useAuth();

  useEffect(() => {
    fetchComments();
  }, [postSlug]);

  const fetchComments = async () => {
    try {
      setLoading(true);
      const response = await commentsAPI.getAll(postSlug);
      if (response.success) {
        setComments(response.data);
      }
    } catch (err) {
      console.error('Failed to load comments:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!user) {
      showError('Login Required', 'Please login to comment');
      return;
    }
    if (!newComment.trim()) {
      showError('Empty Comment', 'Please write something');
      return;
    }
    try {
      setSubmitting(true);
      const response = await commentsAPI.create(postSlug, { content: newComment });
      if (response.success) {
        setComments([response.data, ...comments]);
        setNewComment('');
        showSuccess('Posted!', 'Your comment has been added');
      }
    } catch (err) {
      showError('Failed', err.response?.data?.message || 'Failed to post comment');
    } finally {
      setSubmitting(false);
    }
  };

  const handleEdit = (comment) => {
    setEditingId(comment.id);
    setEditContent(comment.content);
  };

  const handleUpdate = async (commentId) => {
    if (!editContent.trim()) {
      showError('Empty Comment', 'Please write something');
      return;
    }
    try {
      const response = await commentsAPI.update(commentId, { content: editContent });
      if (response.success) {
        setComments(comments.map(c => c.id === commentId ? response.data : c));
        setEditingId(null);
        setEditContent('');
        showSuccess('Updated!', 'Comment updated successfully');
      }
    } catch (err) {
      showError('Failed', 'Failed to update comment');
    }
  };

  const handleDelete = async (commentId) => {
    const result = await showConfirm('Delete Comment?', 'This action cannot be undone', 'Yes, delete it!');
    if (result.isConfirmed) {
      try {
        await commentsAPI.delete(commentId);
        setComments(comments.filter(c => c.id !== commentId));
        showSuccess('Deleted!', 'Comment deleted successfully');
      } catch (err) {
        showError('Failed', 'Failed to delete comment');
      }
    }
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    if (diff < 60) return 'Just now';    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
    if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`;    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
  };

  return (
    <section className="mt-12 glass-effect p-6 sm:p-8 rounded-xl shadow-soft">
      <h2 className="text-2xl font-bold gradient-text mb-6">Comments ({comments.length})</h2>
      {user ? (
        <form onSubmit={handleSubmit} className="mb-8">
          <div className="relative">
            <textarea value={newComment} onChange={(e) => setNewComment(e.target.value)} placeholder="Write a comment..." className="input-field w-full min-h-[100px] resize-none pr-12" maxLength={1000} disabled={submitting} />
            <div className="absolute bottom-3 right-3 text-xs text-gray-400">{newComment.length}/1000</div>
          </div>
          <div className="flex justify-end mt-3">
            <button type="submit" disabled={submitting || !newComment.trim()} className="btn-primary flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
              <FiSend className="w-4 h-4" />
              {submitting ? 'Posting...' : 'Post Comment'}
            </button>
          </div>
        </form>
      ) : (
        <div className="mb-8 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg text-center">
          <p className="text-gray-700">Please login to leave a comment</p>
        </div>
      )}
      {loading ? (
        <div className="text-center py-8">
          <div className="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        </div>
      ) : comments.length === 0 ? (
        <div className="text-center py-8 text-gray-500"><p>No comments yet. Be the first to comment!</p></div>
      ) : (
        <div className="space-y-4">
          {comments.map((comment) => (
            <div key={comment.id} className="bg-white bg-opacity-50 p-4 rounded-lg hover:bg-opacity-70 transition-all duration-300">
              <div className="flex items-start gap-3">
                <div className="w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 flex items-center justify-center flex-shrink-0">
                  <FiUser className="w-5 h-5 text-white" />
                </div>
                <div className="flex-1 min-w-0">
                  <div className="flex items-center justify-between gap-2 mb-2">
                    <div>
                      <p className="font-semibold text-gray-900">{comment.user.name}</p>
                      <p className="text-xs text-gray-500">{formatDate(comment.created_at)}</p>
                    </div>
                    {user && user.id === comment.user.id && (
                      <div className="flex gap-2">
                        <button onClick={() => handleEdit(comment)} className="text-blue-600 hover:text-blue-700 p-1" title="Edit"><FiEdit className="w-4 h-4" /></button>
                        <button onClick={() => handleDelete(comment.id)} className="text-red-600 hover:text-red-700 p-1" title="Delete"><FiTrash2 className="w-4 h-4" /></button>
                      </div>
                    )}
                  </div>
                  {editingId === comment.id ? (
                    <div>
                      <textarea value={editContent} onChange={(e) => setEditContent(e.target.value)} className="input-field w-full min-h-[80px] resize-none mb-2" maxLength={1000} />
                      <div className="flex gap-2">
                        <button onClick={() => handleUpdate(comment.id)} className="text-sm bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-1 rounded-lg hover:shadow-lg transition-all">Save</button>
                        <button onClick={() => { setEditingId(null); setEditContent(''); }} className="text-sm bg-gray-300 text-gray-700 px-4 py-1 rounded-lg hover:bg-gray-400 transition-all">Cancel</button>
                      </div>
                    </div>
                  ) : (
                    <p className="text-gray-700 whitespace-pre-wrap break-words">{comment.content}</p>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </section>
  );
};

export default Comments;
