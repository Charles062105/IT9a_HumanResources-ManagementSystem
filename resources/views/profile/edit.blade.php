<x-app-layout title="My Profile" crumb="Account · Settings">

<div class="page-header">
    <div>
        <div class="page-header-title">My Profile</div>
        <div class="page-header-sub">Manage your account settings and preferences</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:800px">

    {{-- Profile Information --}}
    <div class="form-card" style="grid-column:1 / -1">
        <div class="form-title">Profile information</div>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('patch')

            <div class="form-row">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="{{ $errors->has('name') ? 'input-error' : '' }}">
                    @error('name')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="{{ $errors->has('email') ? 'input-error' : '' }}">
                    @error('email')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>

            @if(auth()->user()->employee)
            <div class="form-row">
                <div class="form-group">
                    <label>Department</label>
                    <input type="text" value="{{ auth()->user()->employee->department }}" readonly style="opacity:0.6">
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <input type="text" value="{{ auth()->user()->employee->position }}" readonly style="opacity:0.6">
                </div>
            </div>
            @endif

            <div style="margin-top:16px">
                <button type="submit" class="btn-primary">Save changes</button>
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="form-card">
        <div class="form-title">Update password</div>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf @method('put')

            <div class="form-group" style="margin-bottom:12px">
                <label>Current password</label>
                <input type="password" name="current_password" autocomplete="current-password" class="{{ $errors->has('current_password') ? 'input-error' : '' }}">
                @error('current_password', 'updatePasswordInfo')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom:12px">
                <label>New password</label>
                <input type="password" name="password" autocomplete="new-password" class="{{ $errors->has('password') ? 'input-error' : '' }}">
                @error('password', 'updatePasswordInfo')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom:16px">
                <label>Confirm new password</label>
                <input type="password" name="password_confirmation" autocomplete="new-password" class="{{ $errors->has('password_confirmation') ? 'input-error' : '' }}">
                @error('password_confirmation', 'updatePasswordInfo')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-primary">Update password</button>
        </form>
    </div>

    {{-- Account Info --}}
    <div class="form-card">
        <div class="form-title">Account details</div>
        <div style="display:flex;flex-direction:column;gap:10px">
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:12px">
                <span style="color:var(--text3)">Account ID</span>
                <span style="font-weight:500;font-family:monospace;font-size:11px">{{ auth()->user()->id }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:12px">
                <span style="color:var(--text3)">Role</span>
                <span style="font-weight:500">
                    <span style="background:{{ auth()->user()->isAdmin() ? 'var(--success-lt)' : 'var(--bg-secondary)' }};color:{{ auth()->user()->isAdmin() ? 'var(--success)' : 'var(--text2)' }};padding:3px 8px;border-radius:4px;font-size:11px">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:12px">
                <span style="color:var(--text3)">Joined</span>
                <span style="font-weight:500;font-size:12px">{{ auth()->user()->created_at->format('M j, Y') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:12px">
                <span style="color:var(--text3)">Status</span>
                <span class="sp sp-ok"><span class="d"></span>Active</span>
            </div>
        </div>
    </div>

</div>

</x-app-layout>