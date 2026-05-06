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

    // Add smooth form feedback
    document.querySelectorAll('form').forEach((form) => {
      form.addEventListener('submit', function(e) {
        // Store form state
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
          const originalText = btn.textContent;
          const originalState = btn.disabled;
          
          // Disable button and show loading state
          btn.disabled = true;
          btn.innerHTML = '<svg class="spinner" style="display:inline-block;width:16px;height:16px;margin-right:6px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .8s linear infinite"></svg>Processing...';
          
          // Restore on response (page reload will handle most cases)
          setTimeout(() => {
            btn.disabled = originalState;
            btn.textContent = originalText;
          }, 2000);
        }
      });
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
