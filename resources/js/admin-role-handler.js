import { ConfirmDialog } from './components';

/**
 * Admin Role Management Handler
 * Manages make-admin and revoke-admin operations
 */
export class AdminRoleHandler {
  /**
   * Initialize Make Admin form
   */
  static initMakeAdmin() {
    const form = document.getElementById('adminRoleForm');
    if (!form) return;

    this.setupFormTracking(form);
    this.setupMakeAdminSubmit(form);
  }

  /**
   * Initialize Revoke Admin form
   */
  static initRevokeAdmin() {
    const form = document.getElementById('revokeRoleForm');
    if (!form) return;

    this.setupFormTracking(form);
    this.setupRevokeAdminSubmit(form);
  }

  /**
   * Track unsaved changes
   */
  static setupFormTracking(form) {
    let formDirty = false;

    form.addEventListener('change', () => {
      formDirty = true;
    });

    form.addEventListener('submit', () => {
      formDirty = false;
    });

    window.addEventListener('beforeunload', (e) => {
      if (formDirty) {
        e.preventDefault();
        e.returnValue = '';
        return '';
      }
    });
  }

  /**
   * Enable button loading state
   */
  static enableButtonLoading(btn) {
    if (!btn || btn.disabled) return;

    const text = btn.querySelector('#submitText');
    const loader = btn.querySelector('#submitLoader');

    btn.disabled = true;
    if (text) text.style.display = 'none';
    if (loader) loader.style.display = 'inline-block';
  }

  /**
   * Disable button loading state
   */
  static disableButtonLoading(btn) {
    if (!btn) return;

    const text = btn.querySelector('#submitText');
    const loader = btn.querySelector('#submitLoader');

    btn.disabled = false;
    if (text) text.style.display = '';
    if (loader) loader.style.display = '';
  }

  /**
   * Setup Make Admin form submission with confirmation modal
   */
  static setupMakeAdminSubmit(form) {
    const submitBtn = document.getElementById('submitBtn');
    const roleInputs = form.querySelectorAll('input[name="role"]');

    form.addEventListener('submit', async (e) => {
      // Prevent if already submitting
      if (form.dataset.submitting === '1') {
        e.preventDefault();
        return;
      }

      // Validate role selection
      const roleSelect = document.querySelector('input[name="role"]:checked');
      if (!roleSelect) {
        e.preventDefault();
        alert('Please select an admin role.');
        return;
      }

      // If not confirmed, prevent submission
      if (form.dataset.confirmed !== '1') {
        e.preventDefault();

        const roleLabelEl = roleSelect.closest('.radio-option')?.querySelector('.radio-title');
        const roleLabel = roleLabelEl ? roleLabelEl.textContent.trim() : 'Admin';
        const userName = form.dataset.userName || 'this user';

        const confirmed = await ConfirmDialog.show(
          'Assign Admin Role',
          `<p>You are about to assign the <strong>${roleLabel}</strong> role to <strong>${userName}</strong>.</p>
           <p>This action will grant admin privileges and send a notification to the user.</p>
           <p>Do you want to proceed?</p>`
        );

        if (confirmed) {
          form.dataset.confirmed = '1';
          AdminRoleHandler.enableButtonLoading(submitBtn);
          form.submit();
        }
        return;
      }

      // Reset confirmation flag after submit
      form.dataset.confirmed = '0';
    });
  }

  /**
   * Setup Revoke Admin form submission with confirmation modal
   */
  static setupRevokeAdminSubmit(form) {
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', async (e) => {
      // Prevent if already submitting
      if (form.dataset.submitting === '1') {
        e.preventDefault();
        return;
      }

      // If not confirmed, prevent submission
      if (form.dataset.confirmed !== '1') {
        e.preventDefault();

        const userName = form.dataset.userName || 'this user';

        const confirmed = await ConfirmDialog.show(
          'Revoke Admin Role',
          `<p>You are about to revoke admin privileges from <strong>${userName}</strong>.</p>
           <p class="confirm-warning"><strong>⚠ Warning:</strong> This action will:</p>
           <ul style="margin: 1rem 0; padding-left: 1.5rem;">
             <li>Immediately remove all admin permissions</li>
             <li>Revert user to employee status</li>
             <li>Send notification to ${userName}</li>
             <li>Be logged in the audit trail</li>
           </ul>
           <p>This action cannot be easily undone. Do you want to proceed?</p>`
        );

        if (confirmed) {
          form.dataset.confirmed = '1';
          AdminRoleHandler.enableButtonLoading(submitBtn);
          form.submit();
        }
        return;
      }

      // Reset confirmation flag after submit
      form.dataset.confirmed = '0';
    });
  }
}

// Initialize on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
  // Determine which page we're on
  if (document.getElementById('adminRoleForm')) {
    AdminRoleHandler.initMakeAdmin();
  } else if (document.getElementById('revokeRoleForm')) {
    AdminRoleHandler.initRevokeAdmin();
  }
});
