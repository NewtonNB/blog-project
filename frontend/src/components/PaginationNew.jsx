import React from 'react';
import { FiChevronLeft, FiChevronRight } from 'react-icons/fi';

const PaginationNew = ({ currentPage = 1, totalPages = 1, onPageChange }) => {
  if (!totalPages || totalPages <= 1) return null;
  if (!onPageChange || typeof onPageChange !== 'function') {
    console.error('PaginationNew: onPageChange prop is required and must be a function', {
      onPageChange,
      type: typeof onPageChange
    });
    return null;
  }

  const getPageNumbers = () => {
    const pages = [];
    const maxVisible = 5;

    if (totalPages <= maxVisible) {
      for (let i = 1; i <= totalPages; i++) {
        pages.push(i);
      }
    } else {
      if (currentPage <= 3) {
        for (let i = 1; i <= 4; i++) pages.push(i);
        pages.push('...');
        pages.push(totalPages);
      } else if (currentPage >= totalPages - 2) {
        pages.push(1);
        pages.push('...');
        for (let i = totalPages - 3; i <= totalPages; i++) pages.push(i);
      } else {
        pages.push(1);
        pages.push('...');
        pages.push(currentPage - 1);
        pages.push(currentPage);
        pages.push(currentPage + 1);
        pages.push('...');
        pages.push(totalPages);
      }
    }
    return pages;
  };

  const handlePageChange = (page) => {
    if (page >= 1 && page <= totalPages && page !== currentPage) {
      onPageChange(page);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  };

  return (
    <div className="flex items-center justify-center gap-2 mt-8 flex-wrap">
      <button
        onClick={() => handlePageChange(currentPage - 1)}
        disabled={currentPage === 1}
        className="flex items-center gap-2 px-4 py-2 rounded-lg border-2 border-gray-300 bg-white hover:border-blue-500 hover:text-blue-600 hover:shadow-md disabled:opacity-40 disabled:cursor-not-allowed transition-all"
      >
        <FiChevronLeft className="w-5 h-5" />
        <span className="hidden sm:inline">Previous</span>
      </button>

      <div className="hidden sm:flex items-center gap-2">
        {getPageNumbers().map((page, index) => (
          page === '...' ? (
            <span key={`ellipsis-${index}`} className="px-3 py-2 text-gray-400 font-medium">...</span>
          ) : (
            <button
              key={`page-${page}`}
              onClick={() => handlePageChange(page)}
              className={`min-w-[44px] px-4 py-2 rounded-lg font-medium transition-all ${
                currentPage === page
                  ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg'
                  : 'border-2 border-gray-300 bg-white hover:border-blue-500 hover:text-blue-600'
              }`}
            >
              {page}
            </button>
          )
        ))}
      </div>

      <div className="sm:hidden px-4 py-2 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg">
        <span className="text-sm font-medium text-gray-700">Page {currentPage} of {totalPages}</span>
      </div>

      <button
        onClick={() => handlePageChange(currentPage + 1)}
        disabled={currentPage === totalPages}
        className="flex items-center gap-2 px-4 py-2 rounded-lg border-2 border-gray-300 bg-white hover:border-blue-500 hover:text-blue-600 hover:shadow-md disabled:opacity-40 disabled:cursor-not-allowed transition-all"
      >
        <span className="hidden sm:inline">Next</span>
        <FiChevronRight className="w-5 h-5" />
      </button>
    </div>
  );
};

export default PaginationNew;
