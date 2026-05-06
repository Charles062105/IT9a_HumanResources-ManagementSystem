<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending Approval – HRMS Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
</head>
<body class="auth-body">

<div class="auth-card" style="max-width:480px">
    <div class="auth-logo">
        <div class="b-icon" style="background:var(--navy)">
            <svg viewBox="0 0 24 24" fill="white" width="18" height="18"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <div>
            <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text)">HRMS Pro</div>
            <div style="font-size:9px;color:var(--text3);letter-spacing:0.9px;text-transform:uppercase">Philippines</div>
        </div>
    </div>

    {{-- Success icon --}}
    <div style="text-align:center;margin:24px 0">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;background:rgba(59,157,208,0.1);border-radius:50%;margin:0 auto 16px">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--info)" stroke-width="2" width="32" height="32" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 12l2 2 4-4"/>
                <circle cx="12" cy="12" r="10"/>
            </svg>
        </div>
    </div>

    <div class="auth-title">Registration submitted</div>
    <div class="auth-sub" style="margin-bottom:24px">
        Thank you for registering! Your account is now pending approval.
    </div>

    <div style="background:rgba(59,157,208,0.08);border:1px solid rgba(59,157,208,0.2);border-radius:10px;padding:16px;margin-bottom:24px;line-height:1.7;font-size:13px;color:var(--text)">
        <div style="display:flex;gap:10px;margin-bottom:12px">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--info)" stroke-width="2" width="18" height="18" style="flex-shrink:0;margin-top:2px" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
            <div>
                <strong>What happens next?</strong>
            </div>
        </div>
        <ol style="margin:0;padding-left:16px">
            <li style="margin-bottom:8px">An HR Administrator will review your registration</li>
            <li style="margin-bottom:8px">You'll receive an email notification when your account is approved</li>
            <li>Once approved, you'll be prompted to complete your employee profile</li>
        </ol>
    </div>

    <div style="background:rgba(244,164,96,0.08);border:1px solid rgba(244,164,96,0.2);border-radius:10px;padding:12px 14px;margin-bottom:20px;font-size:12px;color:var(--text)">
        <strong>Email:</strong> <span style="word-break:break-all;color:var(--text2)">{{ $email }}</span>
        <div style="margin-top:6px;color:var(--text3)">Keep this email handy — you'll use it to sign in once approved.</div>
    </div>

    <div style="margin-bottom:16px">
        <a href="{{ route('login') }}" class="btn-secondary" style="width:100%;justify-content:center;height:40px;font-size:13px;text-decoration:none;display:flex;align-items:center">
            Return to sign in →
        </a>
    </div>

    <div class="auth-footer">
        Already approved? <a href="{{ route('login') }}">Sign in here</a>
    </div>
</div>

</body>
</html>
