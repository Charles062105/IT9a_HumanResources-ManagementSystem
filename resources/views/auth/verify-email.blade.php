<x-guest-layout title="Verify Email">
    <div class="auth-sub" style="margin-bottom:20px">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
    </div>

    @if (session('status') == 'verification-link-sent')
    <div class="flash flash-success" style="border-radius:8px;margin-bottom:16px">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        A new verification link has been sent to your email address.
    </div>
    @endif

    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:20px">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" data-logout-form>
            @csrf
            <button type="submit" style="background:none;border:none;color:var(--text3);font-size:12px;cursor:pointer;text-decoration:underline">Log Out</button>
        </form>
    </div>
</x-guest-layout>