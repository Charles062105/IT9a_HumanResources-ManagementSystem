<x-app-layout title="Revoke Admin Role" crumb="HR · Revoke Admin Role">

<div class="page-header">
    <div>
        <div class="page-header-title">Revoke Admin Role</div>
        <div class="page-header-sub">Remove admin privileges from {{ $user->name }}</div>
    </div>
    <a href="{{ route('requests.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card form-card-narrow">
    <div class="admin-assignment-section">
        <!-- User Summary Card -->
        <div class="user-summary-card">
            <div class="user-summary-avatar av av-lg av-revoke">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="user-summary-info">
                <div class="user-summary-name">{{ $user->name }}</div>
                <div class="user-summary-email">{{ $user->email }}</div>
                <div class="user-summary-current">
                    Current role: <span class="role-current">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                </div>
            </div>
        </div>

        <!-- Warning Alert -->
        <div class="alert alert-warning">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3.05h16.94a2 2 0 0 0 1.71-3.05L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div>
                <div class="alert-title">Permanent Action</div>
                <div class="alert-message">This will immediately revoke all admin privileges from {{ $user->name }}. They will be downgraded to a regular employee.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('users.revoke-admin', $user) }}" id="revokeRoleForm" data-user-name="{{ $user->name }}" data-user-role="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" data-submit-url="{{ route('users.revoke-admin', $user) }}">
            @csrf
            @method('PATCH')

            <!-- Confirmation Message -->
            <div class="confirmation-box">
                <div class="confirmation-icon">!</div>
                <div class="confirmation-text">
                    <p class="confirmation-title">Are you sure?</p>
                    <p class="confirmation-description">
                        Revoking admin role will:
                        <ul>
                            <li>Remove all admin permissions</li>
                            <li>Revert user to employee status</li>
                            <li>Send notification to {{ $user->name }}</li>
                            <li>Be logged in audit trail</li>
                        </ul>
                    </p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions form-actions-right">
                <a href="{{ route('requests.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-danger" id="submitBtn">
                    <span id="submitText">Revoke Admin Role</span>
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
    var form = document.getElementById('revokeRoleForm');
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

            e.preventDefault();
            formConfirmed = true;

            // Show loading state
            submitBtn.disabled = true;
            if (submitText) submitText.style.display = 'none';
            if (submitLoader) submitLoader.style.display = 'inline-block';

            var userName = form.dataset.userName || 'this user';

            // Create confirmation dialog
            var overlay = document.createElement('div');
            overlay.className = 'modal-overlay';
            overlay.style.cssText = 'display:flex;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;';
            overlay.innerHTML = '<div class="modal-dialog" style="background:#fff;border-radius:8px;padding:24px;max-width:400px;width:90%;box-shadow:0 4px 24px rgba(0,0,0,0.15);">' +
                '<div style="font-weight:600;font-size:18px;margin-bottom:12px;color:#dc2626;">Revoke Admin Role</div>' +
                '<div style="color:#64748b;margin-bottom:20px;">' +
                '<p>You are about to revoke admin privileges from <strong>' + userName + '</strong>.</p>' +
                '<p style="margin-top:12px;color:#dc2626;"><strong>⚠ Warning:</strong> This action will:</p>' +
                '<ul style="margin:8px 0;padding-left:20px;">' +
                '<li>Immediately remove all admin permissions</li>' +
                '<li>Revert user to employee status</li>' +
                '<li>Send notification to ' + userName + '</li>' +
                '<li>Be logged in audit trail</li>' +
                '</ul>' +
                '<p style="margin-top:12px;font-style:italic;">This action cannot be easily undone.</p>' +
                '</div>' +
                '<div style="display:flex;gap:12px;justify-content:flex-end;">' +
                '<button type="button" id="cancelRoleBtn" style="padding:8px 16px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;">Cancel</button>' +
                '<button type="button" id="confirmRoleBtn" style="padding:8px 16px;border:none;border-radius:6px;background:#dc2626;color:#fff;cursor:pointer;">Revoke</button>' +
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