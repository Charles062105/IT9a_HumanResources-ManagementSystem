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
<div style="margin:20px 0">
    <div class="card" style="background:linear-gradient(135deg, #667EEA 0%, #764BA2 100%);color:white;padding:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap">
            <div>
                <div style="font-size:13px;opacity:0.9;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">Your Clock</div>
                <div style="font-size:48px;font-weight:700;font-family:monospace;letter-spacing:2px" id="live-clock">{{ now()->format('H:i:s') }}</div>
                <div style="font-size:13px;opacity:0.9;margin-top:8px">{{ now()->format('l, F j, Y') }}</div>
            </div>

            <div style="flex:1;min-width:280px">
                @if($todayAttendance)
                    @if($todayAttendance->time_in && !$todayAttendance->time_out)
                        <div style="text-align:center;margin-bottom:16px">
                            <div style="font-size:13px;opacity:0.9;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.5px">Status</div>
                            <div style="font-size:18px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:8px">
                                <span class="pulse-dot" style="display:inline-block;width:8px;height:8px;background:#4ADE80;border-radius:50%;animation:pulse 2s infinite"></span>
                                Clocked In
                            </div>
                            <div style="font-size:12px;opacity:0.85;margin-top:4px">Since {{ $todayAttendance->time_in->format('H:i A') }}</div>
                        </div>
                        <form method="POST" action="{{ route('attendance.time-out') }}" style="display:flex;gap:8px">
                            @csrf
                            <button type="submit" class="btn-danger" style="flex:1;padding:12px;border:none;cursor:pointer;background:white;color:#764BA2;font-weight:600;border-radius:8px;font-size:13px">
                                🔴 Time Out
                            </button>
                        </form>
                    @elseif($todayAttendance->time_in && $todayAttendance->time_out)
                        <div style="text-align:center">
                            <div style="font-size:13px;opacity:0.9;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.5px">Status</div>
                            <div style="font-size:18px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:12px">
                                <span style="display:inline-block;width:8px;height:8px;background:#9CA3AF;border-radius:50%"></span>
                                Clocked Out
                            </div>
                            <div style="font-size:12px;opacity:0.85;margin-bottom:12px">
                                In: {{ $todayAttendance->time_in->format('H:i A') }} • Out: {{ $todayAttendance->time_out->format('H:i A') }}<br>
                                <strong>Total: {{ $todayAttendance->hours_worked ?? 0 }}h</strong>
                            </div>
                        </div>
                    @else
                        <div style="text-align:center">
                            <div style="font-size:13px;opacity:0.9;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.5px">Status</div>
                            <div style="font-size:18px;font-weight:600;margin-bottom:12px">Not Started</div>
                            <form method="POST" action="{{ route('attendance.time-in') }}" style="display:flex">
                                @csrf
                                <button type="submit" class="btn-success" style="flex:1;padding:12px;border:none;cursor:pointer;background:white;color:#667EEA;font-weight:600;border-radius:8px;font-size:13px">
                                    🟢 Time In
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div style="text-align:center;padding:12px;background:rgba(255,255,255,0.1);border-radius:8px">
                        <div style="font-size:12px;opacity:0.9">No attendance record yet</div>
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
        <div class="kpi-num">{{ $presentDays }}</div>
        <div class="kpi-sub">Last 30 days</div>
        <div class="kpi-delta delta-up">✓ Tracked</div>
    </div>
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#FEF2F2"><svg viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg></div>
            Absent Days
        </div>
        <div class="kpi-num" style="{{ $absentDays > 0 ? 'color:var(--danger)' : '' }}">{{ $absentDays }}</div>
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
            <div class="av" style="background:#DBEAFE;color:#1E40AF">{{ $currentEmployee->initials ?? 'N/A' }}</div>
            <div>
                <div style="font-size:12px;font-weight:500">{{ ucfirst($leave->type) }}</div>
                <div style="font-size:10px;color:var(--text3)">{{ $leave->days }}d · {{ $leave->start_date->format('M j') }}–{{ $leave->end_date->format('M j') }}</div>
            </div>
            <div class="l-acts">
                @if($leave->status === 'pending')
                    <span class="td-muted" style="background:#FEF3C7;color:#92400E;padding:4px 8px;border-radius:4px">Pending</span>
                @elseif($leave->status === 'approved')
                    <span class="td-muted" style="background:#F0FDF4;color:#166534;padding:4px 8px;border-radius:4px">Approved</span>
                @else
                    <span class="td-muted" style="background:#FEE2E2;color:#991B1B;padding:4px 8px;border-radius:4px">Denied</span>
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
                    <div style="font-size:10px;color:var(--text3)">{{ $v->level }} · {{ $v->date->format('M j, Y') }}</div>
                </div>
                <div class="l-acts">
                    @if($v->status === 'open')
                        <span class="td-muted" style="background:#FEE2E2;color:#991B1B;padding:4px 8px;border-radius:4px">Open</span>
                    @else
                        <span class="td-muted" style="background:#F0FDF4;color:#166534;padding:4px 8px;border-radius:4px">Resolved</span>
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
<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .pulse-dot { animation: pulse 2s infinite !important; }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// Live Clock Update
function updateClock() {
    const clockEl = document.getElementById('live-clock');
    if (clockEl) {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        clockEl.textContent = `${hours}:${minutes}:${seconds}`;
    }
}
// Update clock every second
updateClock();
setInterval(updateClock, 1000);

// Chart initialization
new Chart(document.getElementById('attendChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($chartDays) !!},
        datasets: [
            {
                label: 'Present',
                data: {!! json_encode($chartPresent) !!},
                borderColor: '#0F1E38', backgroundColor: 'rgba(15,30,56,0.06)',
                fill: true, tension: 0.4, pointRadius: 3, borderWidth: 2,
                pointBackgroundColor: '#0F1E38'
            },
            {
                label: 'Absent',
                data: {!! json_encode($chartAbsent) !!},
                borderColor: '#DC2626', backgroundColor: 'rgba(220,38,38,0.05)',
                fill: true, tension: 0.4, pointRadius: 3, borderWidth: 2,
                borderDash: [4,3], pointBackgroundColor: '#DC2626'
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 10 }, color: '#94A3B8' } },
            y: { grid: { color: 'rgba(15,30,56,0.04)' }, border: { display: false }, ticks: { font: { size: 10 }, color: '#94A3B8' }, min: 0 }
        }
    }
});
</script>
@endpush

</x-app-layout>
