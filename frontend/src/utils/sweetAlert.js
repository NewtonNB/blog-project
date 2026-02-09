import Swal from 'sweetalert2';

// Success alert
export const showSuccess = (title, text = '') => {
  return Swal.fire({
    icon: 'success',
    title: title,
    text: text,
    confirmButtonColor: '#2563eb',
    timer: 3000,
    timerProgressBar: true,
  });
};

// Error alert
export const showError = (title, text = '') => {
  return Swal.fire({
    icon: 'error',
    title: title,
    text: text,
    confirmButtonColor: '#2563eb',
  });
};

// Warning alert
export const showWarning = (title, text = '') => {
  return Swal.fire({
    icon: 'warning',
    title: title,
    text: text,
    confirmButtonColor: '#2563eb',
  });
};

// Info alert
export const showInfo = (title, text = '') => {
  return Swal.fire({
    icon: 'info',
    title: title,
    text: text,
    confirmButtonColor: '#2563eb',
  });
};

// Confirmation dialog
export const showConfirm = (title, text = '', confirmButtonText = 'Yes, do it!') => {
  return Swal.fire({
    title: title,
    text: text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#2563eb',
    cancelButtonColor: '#ef4444',
    confirmButtonText: confirmButtonText,
    cancelButtonText: 'Cancel',
  });
};

// Loading alert
export const showLoading = (title = 'Please wait...') => {
  return Swal.fire({
    title: title,
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
};

// Close loading
export const closeLoading = () => {
  Swal.close();
};

// Toast notification
export const showToast = (icon, title) => {
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer);
      toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
  });

  return Toast.fire({
    icon: icon,
    title: title,
  });
};
