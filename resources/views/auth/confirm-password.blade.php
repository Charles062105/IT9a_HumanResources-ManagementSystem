<x-guest-layout title="Confirm Password">
    <div class="auth-sub" style="margin-bottom:20px">
        This is a secure area of the application. Please confirm your password before continuing.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group" style="margin-bottom:20px">
            <label>Password</label>
            <input type="password" name="password" required autocomplete="current-password">
            @error('password')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn-primary">Confirm</button>
        </div>
    </form>
</x-guest-layout>