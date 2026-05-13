import { ConfirmDialog, LoadingOverlay } from './components';

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
   * Setup Make Admin form submission with custom modal
   */
  static setupMakeAdminSubmit(form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const roleSelect = document.querySelector('input[name="role"]:checked');
      if (!roleSelect) return;

      const roleLabel = roleSelect.nextElementSibling.querySelector('.radio-title').textContent.trim();
      const userName = form.dataset.userName || 'this user';

      const confirmed = await ConfirmDialog.show(
        'Assign Admin Role',
        `<p>You are about to assign the <strong>${roleLabel}</strong> role to <strong>${userName}</strong>.</p>
         <p>This action will grant admin privileges and send a notification to the user.</p>
         <p>Do you want to proceed?</p>`,
        () => {
          LoadingOverlay.show('Assigning role...');
          form.submit();
        }
      );

      if (!confirmed) {
        return false;
      }
    });
  }

  /**
   * Setup Revoke Admin form submission with custom modal
   */
  static setupRevokeAdminSubmit(form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const userName = form.dataset.userName || 'this user';
      const userRole = form.dataset.userRole || 'Admin';

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
         <p>This action cannot be easily undone. Do you want to proceed?</p>`,
        () => {
          LoadingOverlay.show('Revoking admin role...');
          form.submit();
        }
      );

      if (!confirmed) {
        return false;
      }
    });
  }

  /**
   * Setup button loading state
   */
  static setupButtonLoading(buttonSelector) {
    const btn = document.querySelector(buttonSelector);
    if (!btn) return;

    btn.addEventListener('click', (e) => {
      if (btn.disabled) return;

      btn.disabled = true;
      const text = btn.querySelector('#submitText');
      const loader = btn.querySelector('#submitLoader');

      if (text) text.style.display = 'none';
      if (loader) loader.style.display = 'inline-block';
    });
  }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  // Determine which page we're on
  if (document.getElementById('adminRoleForm')) {
    AdminRoleHandler.initMakeAdmin();
    AdminRoleHandler.setupButtonLoading('#submitBtn');
  } else if (document.getElementById('revokeRoleForm')) {
    AdminRoleHandler.initRevokeAdmin();
    AdminRoleHandler.setupButtonLoading('#submitBtn');
  }
});
