<x-app-layout title="Dashboard" crumb="Home · Overview">

{{-- WELCOME STRIP --}}
<div class="welcome-strip">
    <div>
        <div class="w-greet">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}!</div>
        <div class="w-sub">{{ now()->format('l, F j, Y') }} · Q{{ ceil(now()->month/3) }} FY{{ now()->year }} · Your workforce at a glance.</div>
        <div class="w-tasks">
            @if($openViolations)
            <div class="w-chip"><span class="dot" style="background:#F87171"></span>{{ $openViolations }} violation{{ $openViolations !== 1 ? 's' : '' }} need review</div>
            @endif
            @if($pendingRequests)
                <div class="w-chip"><span class="dot" style="background:#60A5FA"></span>{{ $pendingRequests }} account approval{{ $pendingRequests !== 1 ? 's' : '' }}</div>
            @endif

        </div>
    </div>
    <div class="quick-actions">
        <a href="{{ route('violations.create') }}" class="qa">
            <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>Log violation</span>
        </a>
        <a href="{{ route('performance.create') }}" class="qa">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
            <span>Add review</span>
        </a>
    </div>
</div>

{{-- LIVE CLOCK & TIME IN/OUT --}}
@if($todayAttendance || auth()->user()->employee)
<div style="margin:20px 0">
    <div class="clock-card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap">
            <div>
                <div class="clock-label">Your Clock</div>
                <div style="font-size:48px;font-weight:700;font-family:monospace;letter-spacing:2px" id="live-clock">{{ now()->format('H:i:s') }}</div>
                <div class="clock-date">{{ now()->format('l, F j, Y') }}</div>
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
                        <form method="POST" action="{{ route('attendance.time-out') }}" style="display:flex;flex-direction:column;gap:8px">
                            @csrf
                            <input type="time" name="time" value="{{ now()->format('H:i') }}" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;border-radius:6px;padding:8px 12px;font-size:14px;text-align:center;width:140px;margin:0 auto">
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
                            <form method="POST" action="{{ route('attendance.time-in') }}" style="display:flex;flex-direction:column;gap:8px;align-items:center">
                                @csrf
                                <input type="time" name="time" value="{{ now()->format('H:i') }}" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;border-radius:6px;padding:8px 12px;font-size:14px;text-align:center;width:140px">
                                <button type="submit" class="btn-success" style="flex:1;padding:12px;border:none;cursor:pointer;background:white;color:#667EEA;font-weight:600;border-radius:8px;font-size:13px;width:100%">
                                    🟢 Time In
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div style="text-align:center;padding:12px;background:rgba(255,255,255,0.1);border-radius:8px">
                        <div style="font-size:12px;opacity:0.9">No employee record linked to your account</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- KPI CARDS --}}
<div class="kpi-row">
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#EFF6FF"><svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
            Total Employees
        </div>
        <div class="kpi-num">{{ $totalEmployees }}</div>
        <div class="kpi-sub">Active headcount</div>
        <div class="kpi-delta delta-up">↑ Active</div>
    </div>
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#F0FDF4"><svg viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
            Attendance Rate
        </div>
        <div class="kpi-num" style="{{ $attendanceRate < 85 ? 'color:var(--danger)' : '' }}">{{ $attendanceRate }}<span class="kpi-unit">%</span></div>
        <div class="kpi-sub">{{ $presentToday }} present today</div>
        <div class="kpi-delta {{ $attendanceRate >= 90 ? 'delta-up' : ($attendanceRate >= 80 ? 'delta-warn' : 'delta-down') }}">
            {{ $attendanceRate >= 90 ? '↑ Good' : ($attendanceRate >= 80 ? '⚑ Fair' : '↓ Low') }}
        </div>
    </div>
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#FFFBEB"><svg viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
            Pending Leaves
        </div>
        <div class="kpi-num" style="{{ $pendingLeaves > 0 ? 'color:var(--warn)' : '' }}">{{ $pendingLeaves }}</div>
        <div class="kpi-sub">Awaiting approval</div>
        <div class="kpi-delta {{ $pendingLeaves > 0 ? 'delta-warn' : 'delta-up' }}">
            {{ $pendingLeaves > 0 ? '⚑ Needs attention' : '✓ All clear' }}
        </div>
    </div>
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#FEF2F2"><svg viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            Open Violations
        </div>
        <div class="kpi-num" style="{{ $openViolations > 0 ? 'color:var(--danger)' : '' }}">{{ $openViolations }}</div>
        <div class="kpi-sub">Progressive discipline</div>
        <div class="kpi-delta {{ $openViolations > 0 ? 'delta-down' : 'delta-up' }}">
            {{ $openViolations > 0 ? '↑ '.$openViolations.' open' : '✓ All resolved' }}
        </div>
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
                <canvas id="attendChart" aria-label="7-day attendance chart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Pending leaves</span>
            <a href="{{ route('leaves.index') }}" class="card-action">All →</a>
        </div>
        @forelse($pendingLeaveList as $leave)
        <div class="leave-item">
            <div class="av" style="background:#DBEAFE;color:#1E40AF">{{ $leave->employee->initials }}</div>
            <div>
                <div style="font-size:12px;font-weight:500">{{ $leave->employee->full_name }}</div>
                <div style="font-size:10px;color:var(--text3)">{{ ucfirst($leave->type) }} · {{ $leave->days }}d · {{ $leave->start_date->format('M j') }}–{{ $leave->end_date->format('M j') }}</div>
            </div>
            <div class="l-acts">
                <span class="td-muted">Pending</span>
            </div>
        </div>
        @empty
        <div class="empty-state" style="padding:24px">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg>
            No pending leaves
        </div>
        @endforelse
    </div>
</div>

{{-- BOTTOM ROW --}}
<div class="bot-row">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Violations tracker</span>
            <a href="{{ route('violations.index') }}" class="card-action">All →</a>
        </div>
        <div class="card-body" style="padding:4px 18px 12px">
            @forelse($recentViolations as $v)
            @php $badge = $v->level_badge_color; @endphp
            <div class="viol-item">
                <div class="vb" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }}">{{ $badge['label'] }}</div>
                <div>
                    <div style="font-size:12px;font-weight:500">{{ $v->employee->full_name }}</div>
                    <div style="font-size:10px;color:var(--text3)">{{ $v->level }} · {{ $v->offense }}</div>
                </div>
                <div style="font-size:10px;color:var(--text3);margin-left:auto">{{ $v->date->format('M j') }}</div>
            </div>
            @empty
            <div class="empty-state" style="padding:20px">No open violations</div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Milestones today</span></div>
        <div class="card-body" style="padding:4px 18px 12px">
            @forelse($milestones as $m)
            <div class="ms-item">
                <div class="av" style="background:#FEE2E2;color:#991B1B">{{ $m['employee']->initials }}</div>
                <div>
                    <div style="font-size:12px;font-weight:500">{{ $m['employee']->full_name }}</div>
                    <div style="font-size:10px;color:var(--text3)">
                        {{ $m['employee']->department }}
                        @if($m['type'] === 'anniversary') · {{ $m['years'] }} year{{ $m['years'] !== 1 ? 's' : '' }} @endif
                    </div>
                </div>
                @if($m['type'] === 'birthday')
                <div class="ms-tag" style="background:#EDE9FE;color:#5B21B6">Birthday</div>
                @else
                <div class="ms-tag" style="background:#FEF3C7;color:#92400E">Anniversary</div>
                @endif
            </div>
            @empty
            <div class="empty-state" style="padding:20px">No milestones today</div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
// Set chart data for auto-init via app.js / chart-init.js
window.__chartData = {
    labels: {!! json_encode($chartDays) !!},
    present: {!! json_encode($chartPresent) !!},
    absent: {!! json_encode($chartAbsent) !!}
};
</script>
@endpush

</x-app-layout>
