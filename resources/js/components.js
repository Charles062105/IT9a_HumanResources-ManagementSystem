/**
 * Progress Tracker for Multi-Step Forms
 */

export class ProgressTracker {
  constructor(options = {}) {
    this.steps = options.steps || [];
    this.currentStep = 0;
    this.container = options.container;
    this.onStepChange = options.onStepChange || (() => {});
    
    if (this.container) {
      this.render();
    }
  }

  setSteps(steps) {
    this.steps = steps;
    if (this.container) {
      this.render();
    }
  }

  goToStep(stepIndex) {
    if (stepIndex >= 0 && stepIndex < this.steps.length) {
      this.currentStep = stepIndex;
      this.render();
      this.onStepChange(stepIndex);
      this.scrollToProgress();
    }
  }

  nextStep() {
    if (this.currentStep < this.steps.length - 1) {
      this.goToStep(this.currentStep + 1);
    }
  }

  previousStep() {
    if (this.currentStep > 0) {
      this.goToStep(this.currentStep - 1);
    }
  }

  completeStep() {
    if (this.currentStep < this.steps.length) {
      this.steps[this.currentStep].completed = true;
      this.render();
      if (this.currentStep < this.steps.length - 1) {
        this.nextStep();
      }
    }
  }

  getProgress() {
    const completed = this.steps.filter(s => s.completed).length;
    return Math.round((completed / this.steps.length) * 100);
  }

  render() {
    if (!this.container) return;

    const progress = this.getProgress();
    const html = `
      <div class="progress-tracker">
        <div class="progress-bar-container">
          <div class="progress-bar" style="width: ${progress}%"></div>
        </div>
        <div class="progress-steps">
          ${this.steps.map((step, index) => `
            <div class="progress-step ${index <= this.currentStep ? 'active' : ''} ${step.completed ? 'completed' : ''}">
              <div class="step-indicator">
                ${step.completed ? '✓' : (index + 1)}
              </div>
              <div class="step-label">${step.label}</div>
            </div>
          `).join('')}
        </div>
        <div class="progress-text">${progress}% Complete</div>
      </div>
    `;
    
    this.container.innerHTML = html;
  }

  scrollToProgress() {
    if (this.container) {
      this.container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
}

/**
 * Toast Notification System
 */
export class Toast {
  static show(message, type = 'info', duration = 3000) {
    const id = Date.now();
    const toast = this.createToast(message, type, id);
    
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Auto remove
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 300);
    }, duration);

    return id;
  }

  static createToast(message, type, id) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.id = `toast-${id}`;
    
    const icon = this.getIcon(type);
    toast.innerHTML = `
      <div class="toast-content">
        <div class="toast-icon">${icon}</div>
        <div class="toast-message">${message}</div>
        <button class="toast-close" onclick="this.parentElement.parentElement.classList.remove('show')">×</button>
      </div>
    `;
    
    return toast;
  }

  static getIcon(type) {
    const icons = {
      success: '✓',
      error: '✕',
      warning: '⚠',
      info: 'ℹ',
    };
    return icons[type] || icons.info;
  }
}

/**
 * Confirmation Dialog
 */
export class ConfirmDialog {
  static show(title, message, onConfirm, onCancel) {
    return new Promise((resolve) => {
      const dialog = document.createElement('div');
      dialog.className = 'modal-overlay';
      dialog.innerHTML = `
        <div class="modal-dialog">
          <div class="modal-header">${title}</div>
          <div class="modal-body">${message}</div>
          <div class="modal-footer">
            <button class="btn-secondary" id="cancel-btn">Cancel</button>
            <button class="btn-primary" id="confirm-btn">Confirm</button>
          </div>
        </div>
      `;

      document.body.appendChild(dialog);

      const cancelBtn = dialog.querySelector('#cancel-btn');
      const confirmBtn = dialog.querySelector('#confirm-btn');

      cancelBtn.addEventListener('click', () => {
        dialog.remove();
        onCancel?.();
        resolve(false);
      });

      confirmBtn.addEventListener('click', () => {
        dialog.remove();
        onConfirm?.();
        resolve(true);
      });

      // Click outside to cancel
      dialog.addEventListener('click', (e) => {
        if (e.target === dialog) {
          dialog.remove();
          onCancel?.();
          resolve(false);
        }
      });

      // Trigger animation
      setTimeout(() => dialog.classList.add('show'), 10);
    });
  }
}

/**
 * Loading Overlay
 */
export class LoadingOverlay {
  static show(message = 'Loading...') {
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay show';
    overlay.id = 'loading-overlay';
    overlay.innerHTML = `
      <div class="loading-spinner">
        <div class="spinner"></div>
        <div class="loading-text">${message}</div>
      </div>
    `;
    document.body.appendChild(overlay);
    return overlay;
  }

  static hide() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
      overlay.classList.remove('show');
      setTimeout(() => overlay.remove(), 300);
    }
  }
}
