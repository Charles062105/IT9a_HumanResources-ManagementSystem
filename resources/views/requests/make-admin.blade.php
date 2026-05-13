<x-app-layout title="Assign Admin Role" crumb="HR · Assign Admin Role">

<div class="page-header">
    <div>
        <div class="page-header-title">Assign Admin Role</div>
        <div class="page-header-sub">Select the admin role for {{ $user->name }}</div>
    </div>
</div>

<div class="form-card" style="max-width:600px;margin:0 auto">
    <div style="margin-bottom:32px">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
            <div class="av av-md av-info">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <div style="font-size:14px;font-weight:600">{{ $user->name }}</div>
                <div style="font-size:12px;color:var(--text3);margin-top:2px">{{ $user->email }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('users.assign-admin-role', $user) }}" onsubmit="return confirm('Assign this admin role to {{ addslashes($user->name) }}?');">
            @csrf
            @method('PATCH')

            <div class="form-group" style="margin-bottom:24px">
                <label style="margin-bottom:6px">Current role: <span style="font-weight:600;color:var(--text)">{{ ucfirst($user->role) }}</span></label>
                <label style="margin-top:10px;margin-bottom:12px">Select Admin Role *</label>
                <fieldset class="radio-group" style="border:none;padding:0;margin:0">
                    <legend style="position:absolute;left:-9999px;">Select Admin Role</legend>
                    <!-- Super Admin Option -->
                    <label class="radio-option">
                        <input type="radio" name="role" value="super_admin" {{ old('role') == 'super_admin' ? 'checked' : '' }}>
                        <div class="radio-label">
                            <div class="radio-title">Super Admin</div>
                            <div class="radio-desc">
                                Full system access, manage all features, create other admins, access all reports
                            </div>
                        </div>
                    </label>

                    <!-- Sub-Admin Option -->
                    <label class="radio-option">
                        <input type="radio" name="role" value="sub_admin" {{ old('role') == 'sub_admin' ? 'checked' : '' }}>
                        <div class="radio-label">
                            <div class="radio-title">Sub-Admin</div>
                            <div class="radio-desc">
                                View & manage employees, manage attendance/timesheets, approve leaves (up to 7 days), cannot create admins
                            </div>
                        </div>
                    </label>
                </div>
                @error('role')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Reason for role assignment</label>
                <textarea name="notes" placeholder="Optional notes about why this role is being assigned..." class="{{ $errors->has('notes') ? 'input-error' : '' }}" style="resize: vertical; min-height: 80px;"></textarea>
                @error('notes')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions" style="justify-content:flex-end">
                <a href="{{ route('requests.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Assign Role</button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>
