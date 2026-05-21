<x-app-layout title="Dashboard" crumb="Home · Overview">

{{-- WELCOME STRIP --}}
<div class="welcome-strip">
    <div>
        <div class="w-greet">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}!</div>
        <div class="w-sub">{{ now()->format('l, F j, Y') }} · Q{{ ceil(now()->month/3) }} FY{{ now()->year }} · Your attendance & performance overview.</div>
    </div>
    <div class="quick-actions">
        <a href="{{ route('leaves.create') }}" class="qa">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
            <span>Request leave</span>
        </a>
        <a href="{{ route('timesheets.index') }}" class="qa">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/></svg>
            <span>View timesheets</span>
        </a>
    </div>
</div>

{{-- LIVE CLOCK & TIME IN/OUT --}}
@if($currentEmployee)
<div class="clock-card-wrap" style="margin:20px 0">
    <div class="clock-card">
        <div class="clock-card-inner" style="display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap">
            <div class="clock-info" style="animation:slideInLeft 0.6s ease-out">
                <div class="clock-label">Your Clock</div>
                <div class="clock-time-big" id="live-clock">{{ now()->format('H:i:s') }}</div>
                <div class="clock-date">{{ now()->format('l, F j, Y') }}</div>
            </div>

            <div class="clock-section">
                @if($todayAttendance)
                    @if($todayAttendance->time_in && !$todayAttendance->time_out)
                        <div class="clock-panel clock-panel-bordered">
                            <div class="clock-panel-label">Status</div>
                            <div class="clock-status-text" style="display:flex;align-items:center;justify-content:center;gap:8px">
                                <span class="clock-panel-success-dot"></span>
                                Clocked In
                            </div>
                            <div class="clock-panel-sub">Since {{ $todayAttendance->time_in->format('H:i A') }}</div>
                        </div>
                        <form method="POST" action="{{ route('attendance.time-out') }}">
                            @csrf
                            <button type="submit" class="clock-btn clock-btn-timeout">Time Out</button>
                        </form>
                    @elseif($todayAttendance->time_in && $todayAttendance->time_out)
                        <div class="clock-panel clock-panel-bordered">
                            <div class="clock-panel-label">Status</div>
                            <div class="clock-status-text" style="display:flex;align-items:center;justify-content:center;gap:8px">
                                <span class="clock-panel-gray-dot"></span>
                                Clocked Out
                            </div>
                            <div class="clock-panel-sub">
                                <div style="margin-bottom:8px">In: <strong>{{ $todayAttendance->time_in->format('H:i A') }}</strong> &middot; Out: <strong>{{ $todayAttendance->time_out->format('H:i A') }}</strong></div>
                                <div class="clock-hours-total">Total: {{ $todayAttendance->hours_worked ?? 0 }}h worked</div>
                            </div>
                        </div>
                    @else
                        <div class="clock-panel">
                            <div class="clock-panel-label">Status</div>
                            <div class="clock-status-text" style="color:#FCD34D;font-size:18px">Not Started</div>
                            <form method="POST" action="{{ route('attendance.time-in') }}">
                                @csrf
                                <button type="submit" class="clock-btn clock-btn-timein">Clock In</button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="clock-panel clock-panel-dashed">
                        <div class="clock-panel-label" style="font-size:12px">No attendance record yet</div>
                        <form method="POST" action="{{ route('attendance.time-in') }}">
                            @csrf
                            <button type="submit" class="clock-btn clock-btn-timein" style="padding:12px">Clock In Now</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- PERSONAL STATS --}}
<div class="kpi-row">
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#EFF6FF"><svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
            Present Days
        </div>
        <div class="kpi-num">{{ $presentDays ?? 0 }}</div>
        <div class="kpi-sub">Last 30 days</div>
        <div class="kpi-delta {{ ($presentDays ?? 0) == 0 ? 'delta-down' : 'delta-up' }}">
            {{ ($presentDays ?? 0) == 0 ? '⚠ Review' : '✓ Tracked' }}
        </div>
    </div>
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#FEF2F2"><svg viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></div>
            Absent Days
        </div>
        <div class="kpi-num" style="{{ $absentDays > 0 ? 'color:var(--danger)' : 'color:var(--success)' }}">{{ $absentDays }}</div>
        <div class="kpi-sub">Last 30 days</div>
        <div class="kpi-delta {{ $absentDays > 5 ? 'delta-down' : 'delta-up' }}">
            {{ $absentDays > 5 ? '↑ High' : '✓ Good' }}
        </div>
    </div>
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#F0FDF4"><svg viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            Violations
        </div>
        <div class="kpi-num" style="{{ count($myViolations) > 0 ? 'color:var(--danger)' : '' }}">{{ count($myViolations) }}</div>
        <div class="kpi-sub">On record</div>
        <div class="kpi-delta {{ count($myViolations) > 0 ? 'delta-down' : 'delta-up' }}">
            {{ count($myViolations) > 0 ? '⚠ Review' : '✓ Clear' }}
        </div>
    </div>
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#FFFBEB"><svg viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
            Leave Requests
        </div>
        <div class="kpi-num">{{ count($myLeaves) }}</div>
        <div class="kpi-sub">Total history</div>
        <div class="kpi-delta delta-up">📋 Managed</div>
    </div>
</div>

{{-- MID ROW --}}
<div class="mid-row">
    <div class="card">
        <div class="card-header">
            <span class="card-title">7-day attendance trend</span>
            <a href="{{ route('attendance.index') }}" class="card-action">Full report →</a>
        </div>
        <div class="card-body">
            <div class="legend-row">
                <div class="leg"><div class="leg-sq" style="background:#0F1E38"></div>Present</div>
                <div class="leg"><div class="leg-sq" style="background:#FEE2E2"></div>Absent</div>
            </div>
            <div class="chart-wrap" style="height:160px">
                <canvas id="attendChart" aria-label="7-day personal attendance chart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">My leave requests</span>
            <a href="{{ route('leaves.index') }}" class="card-action">All →</a>
        </div>
        @forelse($myLeaves as $leave)
        <div class="leave-item">
            <div class="av av-info">{{ $currentEmployee->initials ?? 'N/A' }}</div>
            <div>
                <div style="font-size:12px;font-weight:500">{{ ucfirst($leave->type) }}</div>
                <div style="font-size:10px;color:var(--text3)">{{ $leave->days }}d &middot; {{ $leave->start_date->format('M j') }}&ndash;{{ $leave->end_date->format('M j') }}</div>
            </div>
            <div class="l-acts">
                @if($leave->status === 'pending')
                    <span class="sp sp-pend"><span class="d"></span>Pending</span>
                @elseif($leave->status === 'approved')
                    <span class="sp sp-ok"><span class="d"></span>Approved</span>
                @else
                    <span class="sp sp-no"><span class="d"></span>Denied</span>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state" style="padding:24px">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg>
            No leave requests yet
        </div>
        @endforelse
    </div>
</div>

{{-- VIOLATIONS --}}
<div class="bot-row">
    <div class="card">
        <div class="card-header">
            <span class="card-title">My violations</span>
            <a href="{{ route('violations.index') }}" class="card-action">All →</a>
        </div>
        <div class="card-body" style="padding:4px 18px 12px">
            @forelse($myViolations as $v)
            @php $badge = $v->level_badge_color; @endphp
            <div class="viol-item">
                <div class="vb" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }}">{{ $badge['label'] }}</div>
                <div>
                    <div style="font-size:12px;font-weight:500">{{ $v->offense }}</div>
                    <div style="font-size:10px;color:var(--text3)">{{ $v->level }} &middot; {{ $v->date->format('M j, Y') }}</div>
                </div>
                <div class="l-acts">
                    @if($v->status === 'open')
                        <span class="sp sp-open">Open</span>
                    @else
                        <span class="sp sp-ok"><span class="d"></span>Resolved</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:20px">No violations on record</div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
window.__chartData = {
    labels: {!! json_encode($chartDays) !!},
    present: {!! json_encode($chartPresent) !!},
    absent: {!! json_encode($chartAbsent) !!}
};
(function() {
    const clock = document.getElementById('live-clock');
    if (!clock) return;
    function update() {
        const n = new Date();
        clock.textContent = String(n.getHours()).padStart(2, '0') + ':' + String(n.getMinutes()).padStart(2, '0') + ':' + String(n.getSeconds()).padStart(2, '0');
    }
    update();
    setInterval(update, 1000);
})();
</script>
@endpush

</x-app-layout>