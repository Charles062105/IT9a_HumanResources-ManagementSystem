<x-guest-layout title="Reset Password">
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group" style="margin-bottom:14px">
            <label>Email address</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="email">
            @error('email')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom:14px">
            <label>Password</label>
            <input type="password" name="password" required autocomplete="new-password">
            @error('password')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom:20px">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:20px">
            <button type="submit" class="btn-primary">Reset Password</button>
        </div>
    </form>
</x-guest-layout>