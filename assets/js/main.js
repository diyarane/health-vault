// Health Vault — small client-side helpers.
document.addEventListener('DOMContentLoaded', function () {
  // Confirm before any destructive action (delete buttons/forms).
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      var message = el.getAttribute('data-confirm') || 'Are you sure?';
      if (!window.confirm(message)) {
        e.preventDefault();
      }
    });
  });

  // Auto-dismiss alerts after a few seconds.
  document.querySelectorAll('.alert').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity 0.4s ease';
      el.style.opacity = '0';
      setTimeout(function () { el.style.display = 'none'; }, 500);
    }, 6000);
  });
});
