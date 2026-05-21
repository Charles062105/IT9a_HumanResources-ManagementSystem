/**
 * Global form submission handlers and utilities
 */

(function() {
  document.addEventListener('DOMContentLoaded', () => {
    // Add success notification on form success (via Laravel session)
    const alerts = document.querySelectorAll('[data-alert]');
    alerts.forEach((alert) => {
      const type = alert.getAttribute('data-alert');
      const message = alert.textContent.trim();

      if (message) {
        Toast.show(message, type, 5000);
        alert.style.display = 'none';
      }
    });

    // Real-time form field validation feedback
    document.querySelectorAll('input[type="email"]').forEach((input) => {
      input.addEventListener('blur', () => {
        if (input.value && !Validators.email(input.value)) {
          input.classList.add('input-error');
        }
      });
      input.addEventListener('input', () => {
        if (input.value && !Validators.email(input.value)) {
          input.classList.add('input-error');
        } else {
          input.classList.remove('input-error');
        }
      });
    });

    // Phone number formatting
    document.querySelectorAll('input[type="tel"], input[data-phone]').forEach((input) => {
      input.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        let formatted = '';

        if (value.length > 0) {
          if (value.length <= 3) {
            formatted = value;
          } else if (value.length <= 6) {
            formatted = value.slice(0, 3) + '-' + value.slice(3);
          } else {
            formatted = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
          }
        }

        e.target.value = formatted;
      });
    });
  });
})();
