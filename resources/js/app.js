import './bootstrap';

import Alpine from 'alpinejs';
import { FormValidator, Validators, PasswordStrength } from './validations';
import { ProgressTracker, Toast, ConfirmDialog, LoadingOverlay } from './components';
import { initAttendanceChart, initLiveClock } from './chart-init';
import './form-handlers';
import './admin-role-handler';

window.Alpine = Alpine;
window.FormValidator = FormValidator;
window.Validators = Validators;
window.PasswordStrength = PasswordStrength;
window.ProgressTracker = ProgressTracker;
window.Toast = Toast;
window.ConfirmDialog = ConfirmDialog;
window.LoadingOverlay = LoadingOverlay;
window.initAttendanceChart = initAttendanceChart;
window.initLiveClock = initLiveClock;

Alpine.start();

// Initialize form validation and components on page load
document.addEventListener('DOMContentLoaded', () => {
  // Auto-initialize validators on forms with data-validate attribute
  document.querySelectorAll('form[data-validate]').forEach((form) => {
    const rulesJson = form.getAttribute('data-rules');
    const rules = rulesJson ? JSON.parse(rulesJson) : {};
    new FormValidator(form, rules);
  });

  // Initialize password strength indicators
  document.querySelectorAll('input[type="password"][data-strength]').forEach((input) => {
    const strengthContainer = input.getAttribute('data-strength');
    if (strengthContainer) {
      new PasswordStrength(
        `#${input.id}`,
        `.${strengthContainer}`
      );
    }
  });
});
