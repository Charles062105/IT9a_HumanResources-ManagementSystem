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
            @if($pendingLeaves)
            <div class="w-chip"><span class="dot" style="background:#FBBF24"></span>{{ $pendingLeaves }} leave request{{ $pendingLeaves !== 1 ? 's' : '' }} pending</div>
            @endif
            @if($pendingRequests)
            <div class="w-chip"><span class="dot" style="background:#60A5FA"></span>{{ $pendingRequests }} account approval{{ $pendingRequests !== 1 ? 's' : '' }}</div>
            @endif
        </div>
    </div>
    <div class="quick-actions">
        <a href="{{ route('employees.create') }}" class="qa">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            <span>Onboard hire</span>
        </a>
        <a href="{{ route('leaves.create') }}" class="qa">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8" fill="none" stroke="rgba(255,255,255,0.65)" stroke-width="2"/></svg>
            <span>File leave</span>
        </a>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
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
