<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HRMS Pro' }}</title>
    <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700&display=swap" rel="stylesheet">
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
        {{ $slot }}
    </div>
</body>
</html>