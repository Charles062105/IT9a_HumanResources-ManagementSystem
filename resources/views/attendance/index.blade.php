<x-app-layout title="Attendance" crumb="HR · Attendance tracking">

<div class="page-header">
    <div>
        <div class="page-header-title">Attendance</div>
        <div class="page-header-sub">Daily attendance records and time tracking</div>
    </div>
    @if(auth()->user()->employee)
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
        @if(auth()->user()->employee->shift)
        <div class="shift-chip">
            <svg style="width:13px;height:13px;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            {{ auth()->user()->employee->shift->name }}: {{ auth()->user()->employee->shift->start_time->format('H:i') }}–{{ auth()->user()->employee->shift->end_time->format('H:i') }}
        </div>
        @endif

        @if(!$todayRecord)
        <form method="POST" action="{{ route('attendance.time-in') }}" data-clock-form style="margin:0;display:flex;align-items:center;gap:8px;flex-wrap:wrap;display:none" id="clock-in-form">
            @csrf
            <input type="time" name="time" class="form-input form-input-time" placeholder="HH:MM" aria-label="Clock time input" required id="clock-in-time">
        </form>
        <button type="button" class="btn-success confirm-clock-in" data-action="time-in" onclick="handleClockIn()">
            <svg style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Clock In
        </button>
        @elseif($todayRecord->time_in && !$todayRecord->time_out)
        <form method="POST" action="{{ route('attendance.time-out') }}" data-clock-form style="margin:0;display:flex;align-items:center;gap:8px;flex-wrap:wrap;display:none" id="clock-out-form">
            @csrf
            <input type="time" name="time" class="form-input form-input-time" placeholder="HH:MM" aria-label="Clock time input" required id="clock-out-time">
        </form>
        <button type="button" class="btn-danger confirm-clock-out" data-action="time-out" onclick="handleClockOut()">
            <svg style="width:14px;height:14px" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="6" width="12" height="12" rx="2"/></svg>
            Clock Out
            </button>
        </form>
        <div class="clocked-badge">
            <span class="pulse-dot"></span>
            Clocked in {{ $todayRecord->time_in->format('h:i A') }}
        </div>
        @else
        <div class="complete-badge">
            <svg style="width:14px;height:14px;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            @php
                $hw = $todayRecord->hours_worked;
                $whole = floor($hw ?? 0);
                $mins = round(($hw - $whole) * 60);
            @endphp
            Done — {{ $todayRecord->time_out->format('h:i A') }} · {{ $whole }}h {{ sprintf('%02d', $mins) }}m worked
        </div>
        @endif
    </div>
    @endif
</div>

{{-- Live Clock Display for Employees --}}
@if(auth()->user()->isEmployee())
<div class="section-card" style="background:linear-gradient(135deg,#0f1e38 0%,#1a2744 100%);color:white;padding:32px;margin-bottom:24px">
    <div style="display:flex;align-items:center;gap:32px;flex-wrap:wrap">
        <div style="flex:1;text-align:center;min-width:200px">
            <div id="live-clock" style="font-family:'DM Mono',monospace;font-size:3.5rem;font-weight:700;color:white;line-height:1.1">--:--:--</div>
            <div id="live-date" style="text-align:center;font-size:14px;color:rgba(255,255,255,0.6);margin-top:8px;font-weight:500">Today</div>
        </div>
        
        @if($todayRecord)
        <div style="flex:1">
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;background:rgba(255,255,255,0.08);padding:16px;border-radius:8px">
                <div style="flex:1">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.5);margin-bottom:4px">Clocked In</div>
                    <div style="font-size:24px;font-weight:700;font-family:'DM Mono',monospace;color:#4ade80">{{ $todayRecord->time_in?->format('h:i A') ?? '—' }}</div>
                </div>
                <div style="width:1px;height:40px;background:rgba(255,255,255,0.15)"></div>
                <div style="flex:1">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.5);margin-bottom:4px">Clocked Out</div>
                    <div style="font-size:24px;font-weight:700;font-family:'DM Mono',monospace;color:#f87171">{{ $todayRecord->time_out?->format('h:i A') ?? '—' }}</div>
                </div>
            </div>
            @php
                $status = match(true) {
                    $todayRecord->time_out => 'complete',
                    $todayRecord->status === 'absent' => 'absent',
                    $todayRecord->time_in => 'in-progress',
                    default => 'pending'
                };
            @endphp
            <div style="display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:6px;font-size:13px;font-weight:600;width:100%;justify-content:center;
            @if($status === 'complete')
                background:rgba(74,222,128,0.2);color:#4ade80;border:1px solid rgba(74,222,128,0.3)
            @elseif($status === 'absent')
                background:rgba(248,113,113,0.2);color:#f87171;border:1px solid rgba(248,113,113,0.3)
            @elseif($status === 'in-progress')
                background:rgba(59,130,246,0.2);color:#3b82f6;border:1px solid rgba(59,130,246,0.3);animation:pulse 2s infinite
            @else
                background:rgba(107,114,128,0.2);color:rgba(255,255,255,0.6);border:1px solid rgba(107,114,128,0.3)
            @endif
            ">
                @if($status === 'complete')
                    ✓ Day Complete
                @elseif($status === 'absent')
                    Marked Absent
                @elseif($status === 'in-progress')
                    ◉ Clocked In
                @else
                    Not Yet Clocked In
                @endif
            </div>
        </div>
        @else
        <div style="flex:1;text-align:center;padding:16px;color:rgba(255,255,255,0.6);font-size:14px">
            Not yet clocked in
        </div>
        @endif
    </div>
    
    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        @media (max-width: 768px) {
            [style*="display:flex;align-items:center;gap:32px"] {
                flex-direction: column !important;
                gap: 20px !important;
            }
            
            #live-clock {
                font-size: 2.5rem !important;
            }
        }
    </style>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initLiveClock('live-clock');
    const dateEl = document.getElementById('live-date');
    if (dateEl) {
        const options = { weekday: 'long', month: 'short', day: 'numeric' };
        const today = new Date().toLocaleDateString('en-US', options);
        dateEl.textContent = today;
    }
});
</script>
@endif

{{-- KPI Summary Row (admin only) --}}
@if(auth()->user()->isAdmin() && !empty($kpis))
<div class="kpi-row" style="margin-bottom:14px">
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#DCFCE7;color:#16A34A">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            Present
        </div>
        <div class="kpi-num">{{ $kpis['present'] }}<span class="kpi-unit"></span></div>
    </div>
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#FEF3C7;color:#D97706">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            Late
        </div>
        <div class="kpi-num" style="color:#D97706">{{ $kpis['late'] }}<span class="kpi-unit"></span></div>
    </div>
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#FEE2E2;color:#DC2626">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            Absent
        </div>
        <div class="kpi-num" style="color:#DC2626">{{ $kpis['absent'] }}<span class="kpi-unit"></span></div>
    </div>
    <div class="kpi">
        <div class="kpi-label">
            <div class="ki" style="background:#EDE9FE;color:#6D28D9">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/></svg>
            </div>
            On Leave
        </div>
        <div class="kpi-num" style="color:#6D28D9">{{ $kpis['on_leave'] }}<span class="kpi-unit"></span></div>
    </div>
</div>
@endif

<div class="section-card">
    <form method="GET" action="{{ route('attendance.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <input class="finput" type="text" name="search" placeholder="Search employee..." value="{{ request('search') }}">

        <div class="fb-sep"></div>
        <span class="fb-label" style="font-size:9px">From</span>
        <input class="finput" type="date" name="date_from" value="{{ request('date_from') }}" style="width:130px" title="Date from">
        <span class="fb-label" style="font-size:9px">To</span>
        <input class="finput" type="date" name="date_to" value="{{ request('date_to') }}" style="width:130px" title="Date to">

        <div class="fb-sep"></div>
        <select class="fsel" name="department">
            <option value="">All depts</option>
            @foreach($departments as $d)
                <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        <select class="fsel" name="status">
            <option value="">All status</option>
            @foreach(['present','late','absent','half_day','on_leave'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="fbtn">Apply</button>
        @if(request()->anyFilled(['search','date_from','date_to','department','status']))
            <a href="{{ route('attendance.index') }}" class="fbtn ghost">Reset</a>
        @endif
        <span class="f-results">{{ $records->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Shift</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th style="text-align:right">Hours</th>
                    <th>Status</th>
                    @if(auth()->user()->isAdmin())
                    <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr class="{{ auth()->user()->isAdmin() && auth()->user()->employee?->id === $r->employee_id ? 'row-own' : '' }}">
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="av" style="background:#DBEAFE;color:#1E40AF">{{ $r->employee?->initials }}</div>
                            <span class="td-bold">{{ $r->employee?->full_name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $r->employee?->department }}</td>
                    <td class="td-muted" style="max-width:140px;white-space:normal;word-break:break-word;font-size:11px">
                        @if($r->employee?->shift)
                            <span style="font-weight:500">{{ $r->employee->shift->name }}</span>
                            <span style="color:var(--text3)">({{ $r->employee->shift->start_time->format('H:i') }}–{{ $r->employee->shift->end_time->format('H:i') }})</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="td-muted" style="white-space:nowrap">{{ $r->date->format('M j, Y') }}</td>
                    <td class="{{ $r->status === 'late' ? 'td-late' : 'td-muted' }}" style="font-family:'DM Mono',monospace;font-size:11px">
                        {{ $r->time_in?->format('h:i A') ?? '—' }}
                        @if($r->status === 'late')
                            <span class="late-arrow">↑</span>
                        @endif
                    </td>
                    <td class="td-muted" style="font-family:'DM Mono',monospace;font-size:11px">{{ $r->time_out?->format('h:i A') ?? '—' }}</td>
                    <td style="text-align:right;font-weight:600;font-size:12px;white-space:nowrap">
                        @if($r->hours_worked)
                            @php
                                $whole = floor($r->hours_worked);
                                $mins = round(($r->hours_worked - $whole) * 60);
                            @endphp
                            {{ $whole }}h {{ sprintf('%02d', $mins) }}m
                        @else
                            <span style="color:var(--text3)">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                        $sc = match($r->status) {
                            'present'  => 'sp-ok',
                            'late'     => 'sp-late',
                            'absent'   => 'sp-no',
                            'half_day' => 'sp-half',
                            'on_leave' => 'sp-lv',
                            default    => 'sp-pend',
                        };
                        @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst(str_replace('_',' ',$r->status)) }}</span>
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('attendance.edit', $r) }}" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px;text-decoration:none">Edit</a>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? '9' : '8' }}">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            No attendance records found
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
        <div class="pagination-wrap">{{ $records->links() }}</div>
    @endif
</div>

@push('scripts')
<script>
// Set time inputs to local time
document.querySelectorAll('.local-time-input').forEach(function(input) {
    var now = new Date();
    var h = String(now.getHours()).padStart(2, '0');
    var m = String(now.getMinutes()).padStart(2, '0');
    input.value = h + ':' + m;
});

// Disable submit button on clock forms
document.querySelectorAll('[data-clock-form]').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        var btn = form.querySelector('[data-submit-btn]');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Processing...';
        }
    });
});
</script>
@endpush

<style>
.time-input {
    background: var(--surface2);
    border: 1px solid var(--border2);
    border-radius: 6px;
    padding: 7px 10px;
    font-size: 13px;
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
    outline: none;
}
.time-input:focus { border-color: rgba(37,99,235,0.45); }
.shift-chip {
    display: flex; align-items: center; gap: 6px;
    padding: 7px 12px;
    background: #E0E7FF; color: #3730A3;
    border-radius: 6px; font-size: 12px; font-weight: 500;
}
.clocked-badge {
    display: flex; align-items: center; gap: 7px;
    padding: 9px 14px;
    background: #FEF3C7; color: #92400E;
    border-radius: 6px; font-size: 12px; font-weight: 600;
}
.complete-badge {
    display: flex; align-items: center; gap: 6px;
    padding: 9px 14px;
    background: #DCFCE7; color: #15803D;
    border-radius: 6px; font-size: 12px; font-weight: 600;
}
.pulse-dot {
    width: 8px; height: 8px;
    background: #92400E;
    border-radius: 50%;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>

@push('scripts')
<script>
let isSubmitting = false;

function handleClockIn() {
    const form = document.getElementById('clock-in-form');
    const timeInput = document.getElementById('clock-in-time');
    const time = timeInput.value || 'Current time';
    
    if (confirm(`You are about to clock in at ${time}. This will mark you as present. Continue?`)) {
        isSubmitting = true;
        const btn = event.target.closest('button');
        btn.disabled = true;
        btn.textContent = '⏳ Recording...';
        
        form.submit();
    }
}

function handleClockOut() {
    const form = document.getElementById('clock-out-form');
    const timeInput = document.getElementById('clock-out-time');
    const time = timeInput.value || 'Current time';
    
    if (confirm(`You are about to clock out at ${time}. After clocking out, you won't be able to modify your time for today. Continue?`)) {
        isSubmitting = true;
        const btn = event.target.closest('button');
        btn.disabled = true;
        btn.textContent = '⏳ Recording...';
        
        form.submit();
    }
}

// Validate time inputs - prevent future times
document.getElementById('clock-in-time')?.addEventListener('change', function() {
    if (!this.value) return;
    const [hours, minutes] = this.value.split(':').map(Number);
    const inputTime = new Date();
    inputTime.setHours(hours, minutes, 0);
    
    if (inputTime > new Date()) {
        alert('Cannot use future time. Please select current or past time.');
        this.value = '';
    }
});

document.getElementById('clock-out-time')?.addEventListener('change', function() {
    if (!this.value) return;
    const [hours, minutes] = this.value.split(':').map(Number);
    const inputTime = new Date();
    inputTime.setHours(hours, minutes, 0);
    
    if (inputTime > new Date()) {
        alert('Cannot use future time. Please select current or past time.');
        this.value = '';
    }
});

// Set default time to current time
document.addEventListener('DOMContentLoaded', function() {
    [document.getElementById('clock-in-time'), document.getElementById('clock-out-time')].forEach(input => {
        if (input) {
            const now = new Date();
            input.value = String(now.getHours()).padStart(2, '0') + ':' + 
                         String(now.getMinutes()).padStart(2, '0');
        }
    });
});
</script>
@endpush
    padding: 9px 14px;
    background: #F0FDF4; color: #166534;
    border-radius: 6px; font-size: 12px; font-weight: 600;
}
.row-own { background: rgba(37,99,235,0.03); }
.td-late { color: #D97706 !important; font-weight: 600; }
.late-arrow {
    font-size: 10px; color: #D97706;
    display: inline-block; margin-left: 2px;
}
</style>

</x-app-layout>