<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Pro – Register</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
</head>
<body class="auth-body">

<div class="auth-card" style="max-width:440px">
    <div class="auth-logo">
        <div class="b-icon" style="background:var(--navy)">
            <svg viewBox="0 0 24 24" fill="white" width="18" height="18"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <div>
            <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text)">HRMS Pro</div>
            <div style="font-size:9px;color:var(--text3);letter-spacing:0.9px;text-transform:uppercase">Philippines</div>
        </div>
    </div>

    <div class="auth-title">Create account</div>
    <div class="auth-sub" style="margin-bottom:24px">Your account will require admin approval before access is granted.</div>

    @if($errors->any())
    <div class="flash flash-error" style="border-radius:8px;margin-bottom:16px">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}" data-validate data-rules='{"name":{"fn":"Validators.required"},"email":{"fn":"Validators.email"},"password":{"fn":"Validators.password"},"password_confirmation":{"fn":"Validators.passwordConfirm"}}'>
        @csrf
        <div class="form-group" style="margin-bottom:12px">
            <label>Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')<div class="error-msg" style="display:block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group" style="margin-bottom:12px">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')<div class="error-msg" style="display:block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group" style="margin-bottom:12px">
            <label>Password</label>
            <input type="password" name="password" required autocomplete="new-password" id="password-input" data-strength="strength-indicator">
            <div class="password-strength-container">
              <div class="password-strength-bar">
                <div class="password-strength-indicator strength-indicator" style="width:0%"></div>
              </div>
            </div>
            <div style="font-size:11px;color:var(--text3);margin-top:6px;line-height:1.5">
              Must contain: 8+ characters, uppercase, lowercase, number
            </div>
            @error('password')<div class="error-msg" style="display:block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group" style="margin-bottom:22px">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')<div class="error-msg" style="display:block">{{ $message }}</div>@enderror
        </div>

        <div style="background:var(--info-lt);border-radius:8px;padding:10px 12px;margin-bottom:18px;font-size:11px;color:var(--info);line-height:1.6">
            ℹ After registering, your account will be reviewed by an HR Administrator. You will receive access once approved.
        </div>

        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;height:40px;font-size:13px">
            Create account →
        </button>
    </form>

    <div class="auth-footer" style="margin-top:18px">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
</div>

</body>
</html>
