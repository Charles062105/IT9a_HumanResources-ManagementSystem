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

        <form method="POST" action="{{ route('users.revoke-admin', $user) }}" id="revokeRoleForm" data-user-name="{{ $user->name }}" data-user-role="{{ ucfirst(str_replace('_', ' ', $user->role)) }}">
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

</x-app-layout>
