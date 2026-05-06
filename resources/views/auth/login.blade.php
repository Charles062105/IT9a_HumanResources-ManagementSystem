<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Pro – Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
</head>
<body class="auth-body">

<div style="display:grid;grid-template-columns:1fr 400px;min-height:100vh;max-width:960px;margin:0 auto;gap:0">

    {{-- Left panel --}}
    <div style="background:var(--navy);display:flex;flex-direction:column;justify-content:center;padding:48px;border-radius:var(--radius) 0 0 var(--radius)">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:40px">
            <div class="b-icon"><svg viewBox="0 0 24 24" fill="white" width="18" height="18"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div>
            <div>
                <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:#fff">HRMS Pro</div>
                <div style="font-size:9px;color:rgba(255,255,255,0.3);letter-spacing:0.9px;text-transform:uppercase">Philippines</div>
            </div>
        </div>
        <div style="font-family:'Syne',sans-serif;font-size:26px;font-weight:700;color:#fff;line-height:1.25;margin-bottom:14px">
            Your workforce,<br>fully in control.
        </div>
        <div style="font-size:13px;color:rgba(255,255,255,0.45);line-height:1.7;margin-bottom:32px">
            DOLE-compliant HR management for Philippine organizations. Track attendance, manage leaves, enforce progressive discipline, and review performance — all in one place.
        </div>
        @php $features = ['Progressive discipline tracking (5 levels)','Real-time attendance & time logging','Leave requests & approvals','Performance reviews & scoring','Government ID compliance (SSS, Pag-IBIG, PhilHealth)']; @endphp
        @foreach($features as $f)
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;font-size:12px;color:rgba(255,255,255,0.6)">
            <div style="width:16px;height:16px;border-radius:50%;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            {{ $f }}
        </div>
        @endforeach
    </div>

    {{-- Right panel --}}
    <div style="background:var(--surface);display:flex;flex-direction:column;justify-content:center;padding:48px;border-radius:0 var(--radius) var(--radius) 0">
        <div class="auth-title">Welcome back</div>
        <div class="auth-sub" style="margin-bottom:28px">Sign in to your HR dashboard</div>

        @if(session('status'))
        <div class="flash flash-success" style="border-radius:8px;margin-bottom:16px">{{ session('status') }}</div>
        @endif

        @if($errors->any())
        <div class="flash flash-error" style="border-radius:8px;margin-bottom:16px">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group" style="margin-bottom:14px">
                <label>Email address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
            </div>
            <div class="form-group" style="margin-bottom:20px">
                <label style="display:flex;justify-content:space-between;align-items:center">
                    Password
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:11px;color:var(--info);text-decoration:none;font-weight:400">Forgot password?</a>
                    @endif
                </label>
                <input type="password" name="password" required autocomplete="current-password">
            </div>
            <label style="display:flex;align-items:center;gap:7px;font-size:12px;color:var(--text2);font-weight:400;cursor:pointer;margin-bottom:20px">
                <input type="checkbox" name="remember" style="width:auto;height:auto">
                Keep me signed in
            </label>
            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;height:40px;font-size:13px">
                Sign in →
            </button>
        </form>

        @if(Route::has('register'))
        <div style="text-align:center;margin-top:20px;font-size:12px;color:var(--text3)">
            Don't have an account? <a href="{{ route('register') }}" style="color:var(--info);text-decoration:none;font-weight:500">Register</a>
        </div>
        @endif

        <div style="margin-top:32px;padding-top:20px;border-top:1px solid var(--border);font-size:10px;color:var(--text3);text-align:center;line-height:1.6">
            <strong style="color:var(--text2)">Demo credentials</strong><br>
            Admin: admin@hrms.ph · password<br>
            Employee: juan.delacruz@hrms.ph · password
        </div>
    </div>
</div>

</body>
</html>
