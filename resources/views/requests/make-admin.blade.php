<x-app-layout title="Assign Admin Role" crumb="HR · Assign Admin Role">

<div class="page-header">
    <div>
        <div class="page-header-title">Assign Admin Role</div>
        <div class="page-header-sub">Select the admin role for {{ $user->name }}</div>
    </div>
    <a href="{{ route('requests.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card form-card-narrow">
    <div class="admin-assignment-section">
        <!-- User Summary Card -->
        <div class="user-summary-card">
            <div class="user-summary-avatar av av-lg av-info">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="user-summary-info">
                <div class="user-summary-name">{{ $user->name }}</div>
                <div class="user-summary-email">{{ $user->email }}</div>
                <div class="user-summary-current">
                    Current role: <span class="role-current">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('users.assign-admin-role', $user) }}" id="adminRoleForm" data-user-name="{{ $user->name }}">
            @csrf
            @method('PATCH')

            <!-- Role Selection -->
            <div class="form-group form-group-full">
                <label for="roleSelect" class="form-label">Select Admin Role <span class="required">*</span></label>
                <fieldset class="radio-group">
                    <legend class="sr-only">Select Admin Role</legend>

                    <!-- Super Admin Option -->
                    <label class="radio-option">
                        <input
                            type="radio"
                            id="role_super_admin"
                            name="role"
                            value="super_admin"
                            {{ old('role') == 'super_admin' ? 'checked' : '' }}
                            required
                        >
                        <div class="radio-label">
                            <div class="radio-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="role-icon"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/><path d="M12 1v6m0 6v6"/></svg>
                                Super Admin
                            </div>
                            <div class="radio-desc">
                                Full system access, manage all features, create other admins, access all reports
                            </div>
                        </div>
                    </label>

                    <!-- Sub-Admin Option -->
                    <label class="radio-option">
                        <input
                            type="radio"
                            id="role_sub_admin"
                            name="role"
                            value="sub_admin"
                            {{ old('role') == 'sub_admin' ? 'checked' : '' }}
                            required
                        >
                        <div class="radio-label">
                            <div class="radio-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="role-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                Sub-Admin
                            </div>
                            <div class="radio-desc">
                                View & manage employees, manage attendance/timesheets, approve leaves (up to 7 days), cannot create admins
                            </div>
                        </div>
                    </label>
                </fieldset>
                @error('role')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <!-- Notes Field -->
            <div class="form-group form-group-full">
                <label for="notes">Reason for role assignment <span class="optional">(optional)</span></label>
                <textarea
                    id="notes"
                    name="notes"
                    placeholder="Add notes about why this role is being assigned..."
                    class="{{ $errors->has('notes') ? 'input-error' : '' }}"
                ></textarea>
                @error('notes')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions form-actions-right">
                <a href="{{ route('requests.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary" id="submitBtn">
                    <span id="submitText">Assign Role</span>
                    <span id="submitLoader" class="btn-loader"></span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function() {
    // Inline fallback handler - ensures form works even if module script fails
    var form = document.getElementById('adminRoleForm');
    var submitBtn = document.getElementById('submitBtn');
    var submitText = document.getElementById('submitText');
    var submitLoader = document.getElementById('submitLoader');
    var formConfirmed = false;

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Check if already submitted or confirmed
            if (formConfirmed) {
                return true;
            }

            // Validate role selection
            var roleSelect = document.querySelector('input[name="role"]:checked');
            if (!roleSelect) {
                e.preventDefault();
                alert('Please select an admin role before submitting.');
                return false;
            }

            e.preventDefault();
            formConfirmed = true;

            // Show loading state
            submitBtn.disabled = true;
            if (submitText) submitText.style.display = 'none';
            if (submitLoader) submitLoader.style.display = 'inline-block';

            // Get role label for confirmation
            var roleLabelEl = roleSelect.closest('.radio-option') && roleSelect.closest('.radio-option').querySelector('.radio-title');
            var roleLabel = roleLabelEl ? roleLabelEl.textContent.trim() : 'Admin';
            var userName = form.dataset.userName || 'this user';

            // Create confirmation dialog
            var overlay = document.createElement('div');
            overlay.className = 'modal-overlay';
            overlay.style.cssText = 'display:flex;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;';
            overlay.innerHTML = '<div class="modal-dialog" style="background:#fff;border-radius:8px;padding:24px;max-width:400px;width:90%;box-shadow:0 4px 24px rgba(0,0,0,0.15);">' +
                '<div style="font-weight:600;font-size:18px;margin-bottom:12px;">Assign Admin Role</div>' +
                '<div style="color:#64748b;margin-bottom:20px;">' +
                '<p>You are about to assign the <strong>' + roleLabel + '</strong> role to <strong>' + userName + '</strong>.</p>' +
                '<p style="margin-top:8px;">This action will grant admin privileges and send a notification to the user.</p>' +
                '</div>' +
                '<div style="display:flex;gap:12px;justify-content:flex-end;">' +
                '<button type="button" id="cancelRoleBtn" style="padding:8px 16px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;">Cancel</button>' +
                '<button type="button" id="confirmRoleBtn" style="padding:8px 16px;border:none;border-radius:6px;background:#3b82f6;color:#fff;cursor:pointer;">Confirm</button>' +
                '</div>' +
                '</div>';
            document.body.appendChild(overlay);

            document.getElementById('cancelRoleBtn').addEventListener('click', function() {
                overlay.remove();
                formConfirmed = false;
                submitBtn.disabled = false;
                if (submitText) submitText.style.display = '';
                if (submitLoader) submitLoader.style.display = '';
            });

            document.getElementById('confirmRoleBtn').addEventListener('click', function() {
                overlay.remove();
                form.submit();
            });

            // Close on overlay click
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.remove();
                    formConfirmed = false;
                    submitBtn.disabled = false;
                    if (submitText) submitText.style.display = '';
                    if (submitLoader) submitLoader.style.display = '';
                }
            });
        });
    }
})();
</script>
@endpush

</x-app-layout>
