/**
 * Form Validation Utilities
 */

export const Validators = {
  // Email validation
  email: (value) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(value) ? null : 'Invalid email address';
  },

  // Password validation
  password: (value) => {
    if (value.length < 8) return 'Password must be at least 8 characters';
    if (!/[A-Z]/.test(value)) return 'Password must contain uppercase letter';
    if (!/[a-z]/.test(value)) return 'Password must contain lowercase letter';
    if (!/[0-9]/.test(value)) return 'Password must contain number';
    return null;
  },

  // Password confirmation
  passwordConfirm: (password, confirmation) => {
    return password === confirmation ? null : 'Passwords do not match';
  },

  // Phone number validation
  phone: (value) => {
    if (!value) return null; // Optional field
    const re = /^[\d\s\-\+\(\)]+$/;
    return re.test(value) && value.replace(/\D/g, '').length >= 10
      ? null
      : 'Invalid phone number';
  },

  // Required field
  required: (value) => {
    return value && value.trim() ? null : 'This field is required';
  },

  // Minimum length
  minLength: (value, min) => {
    return !value || value.length >= min ? null : `Minimum ${min} characters required`;
  },

  // Date validation
  date: (value) => {
    if (!value) return null;
    const date = new Date(value);
    return date instanceof Date && !isNaN(date) ? null : 'Invalid date';
  },

  // Future date validation
  futureDate: (value) => {
    if (!value) return null;
    const date = new Date(value);
    return date > new Date() ? null : 'Date must be in the future';
  },

  // Past date validation
  pastDate: (value) => {
    if (!value) return null;
    const date = new Date(value);
    return date < new Date() ? null : 'Date must be in the past';
  },
};

/**
 * Form Handler with real-time validation
 */
export class FormValidator {
  constructor(formSelector, rules = {}) {
    this.form = document.querySelector(formSelector);
    this.rules = rules;
    this.errors = {};
    this.isValid = true;

    if (this.form) {
      this.initializeListeners();
    }
  }

  initializeListeners() {
    // Real-time validation on input
    this.form.querySelectorAll('input, textarea, select').forEach((field) => {
      field.addEventListener('blur', () => this.validateField(field));
      field.addEventListener('input', (e) => {
        if (this.errors[field.name]) {
          this.validateField(field);
        }
      });
    });

    // Form submission validation
    this.form.addEventListener('submit', (e) => {
      if (!this.validate()) {
        e.preventDefault();
        this.focusFirstError();
      }
    });
  }

  validateField(field) {
    const { name, value } = field;
    const fieldRules = this.rules[name];

    if (!fieldRules) return true;

    const validators = Array.isArray(fieldRules) ? fieldRules : [fieldRules];
    let error = null;

    for (const validator of validators) {
      if (typeof validator === 'function') {
        error = validator(value);
      } else if (typeof validator === 'object') {
        const { fn, params } = validator;
        error = fn(value, ...params);
      }
      if (error) break;
    }

    this.updateFieldError(field, error);
    return !error;
  }

  validate() {
    this.errors = {};
    this.isValid = true;

    this.form.querySelectorAll('input, textarea, select').forEach((field) => {
      if (!this.validateField(field)) {
        this.isValid = false;
      }
    });

    return this.isValid;
  }

  updateFieldError(field, error) {
    const group = field.closest('.form-group');
    if (!group) return;

    if (error) {
      this.errors[field.name] = error;
      field.classList.add('input-error');
      let errorDiv = group.querySelector('.error-msg');
      if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-msg';
        group.appendChild(errorDiv);
      }
      errorDiv.textContent = error;
      errorDiv.style.display = 'block';
    } else {
      delete this.errors[field.name];
      field.classList.remove('input-error');
      const errorDiv = group.querySelector('.error-msg');
      if (errorDiv) {
        errorDiv.style.display = 'none';
      }
    }
  }

  focusFirstError() {
    const firstErrorField = this.form.querySelector('.input-error');
    if (firstErrorField) {
      firstErrorField.focus();
      firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }

  getErrors() {
    return this.errors;
  }

  clearErrors() {
    this.form.querySelectorAll('.input-error').forEach((field) => {
      field.classList.remove('input-error');
      const group = field.closest('.form-group');
      if (group) {
        const errorDiv = group.querySelector('.error-msg');
        if (errorDiv) errorDiv.style.display = 'none';
      }
    });
    this.errors = {};
  }
}

/**
 * Password strength indicator
 */
export class PasswordStrength {
  constructor(passwordInputSelector, strengthIndicatorSelector) {
    this.input = document.querySelector(passwordInputSelector);
    this.indicator = document.querySelector(strengthIndicatorSelector);

    if (this.input && this.indicator) {
      this.input.addEventListener('input', () => this.updateStrength());
    }
  }

  updateStrength() {
    const password = this.input.value;
    let strength = 0;
    let color = '#ddd';
    let label = 'Very weak';

    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    if (password.length >= 12) strength++;

    if (strength < 2) {
      color = '#ef4444';
      label = 'Very weak';
    } else if (strength < 3) {
      color = '#f97316';
      label = 'Weak';
    } else if (strength < 4) {
      color = '#eab308';
      label = 'Fair';
    } else if (strength < 5) {
      color = '#84cc16';
      label = 'Good';
    } else {
      color = '#22c55e';
      label = 'Strong';
    }

    this.indicator.style.background = color;
    this.indicator.style.width = (strength * 20) + '%';
    this.indicator.title = label;
  }
}
