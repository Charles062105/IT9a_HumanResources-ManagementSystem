<x-guest-layout title="Forgot Password">
    <div class="auth-sub" style="margin-bottom:20px">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </div>

    @if (session('status'))
    <div class="flash flash-success" style="border-radius:8px;margin-bottom:16px">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group" style="margin-bottom:16px">
            <label>Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="name@company.com">
            @error('email')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:20px">
            <button type="submit" class="btn-primary">Email Password Reset Link</button>
        </div>
    </form>
</x-guest-layout>