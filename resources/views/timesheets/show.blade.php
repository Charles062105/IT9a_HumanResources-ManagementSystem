<x-app-layout title="Timesheet Details" crumb="HR · Timesheets · Details">

<div class="page-header">
    <div>
        <div class="page-header-title">Timesheet Details</div>
        <div class="page-header-sub">{{ $timesheet->week_label }}</div>
    </div>
    <div style="display:flex;gap:8px">
        @if(auth()->user()->isAdmin() || auth()->user()->employee?->id === $timesheet->employee_id)
        <a href="{{ route('timesheets.edit', $timesheet) }}" class="btn-primary" style="text-decoration:none">Edit</a>
        @endif
        <a href="{{ auth()->user()->isAdmin() ? route('timesheets.index') : route('timesheets.my') }}" class="btn-secondary">← Back</a>
    </div>
</div>

<div class="form-card" style="max-width:560px">
    <div class="form-title">Submission Summary</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
        <div class="form-group">
            <label>Employee</label>
            <div style="display:flex;align-items:center;gap:8px;padding-top:4px">
                <div class="av av-sm" style="background:#DBEAFE;color:#1E40AF;font-size:10px">{{ $timesheet->employee?->initials }}</div>
                <div>
                    <div style="font-size:13px;font-weight:500;color:var(--text)">{{ $timesheet->employee?->full_name ?? '—' }}</div>
                    <div style="font-size:11px;color:var(--text3)">{{ $timesheet->employee?->department }}</div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Status</label>
            @php
            $sc = match($timesheet->status) {
                'approved' => 'sp-ok',
                'rejected' => 'sp-no',
                default => 'sp-pend',
            };
            @endphp
            <div style="padding-top:6px"><span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($timesheet->status) }}</span></div>
        </div>
        <div class="form-group">
            <label>Week Range</label>
            <div style="font-size:13px;font-weight:500;color:var(--text);padding-top:8px">{{ $timesheet->week_label }}</div>
            <div style="font-size:11px;color:var(--text3);margin-top:2px">
                {{ $timesheet->week_start?->format('M j, Y') }} — {{ $timesheet->week_end?->format('M j, Y') }}
            </div>
        </div>
        <div class="form-group">
            <label>Submitted</label>
            <div style="font-size:13px;font-weight:500;color:var(--text);padding-top:8px">{{ $timesheet->submitted_at?->format('M j, Y') }}</div>
        </div>
    </div>

    <div class="divider"></div>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px">
        <div class="form-group">
            <label>Total Hours</label>
            <div style="font-size:22px;font-weight:700;color:var(--text);font-family:'Syne',sans-serif">{{ $timesheet->total_hours }}<span style="font-size:13px;color:var(--text3);font-weight:400">h</span></div>
        </div>
        <div class="form-group">
            <label>Overtime Hours</label>
            <div style="font-size:22px;font-weight:700;color:var(--text3);font-family:'Syne',sans-serif">{{ $timesheet->ot_hours ?? 0 }}<span style="font-size:13px;font-weight:400">h</span></div>
        </div>
        @if($timesheet->approver)
        <div class="form-group">
            <label>Reviewed By</label>
            <div style="font-size:13px;font-weight:500;color:var(--text);padding-top:8px">{{ $timesheet->approver->name }}</div>
            <div style="font-size:11px;color:var(--text3);margin-top:2px">{{ $timesheet->approver->isAdmin() ? 'Admin' : 'Manager' }}</div>
        </div>
        @endif
    </div>

    @if($timesheet->notes)
    <div class="divider"></div>
    <div class="form-group" style="margin-bottom:0">
        <label>Notes</label>
        <div style="font-size:13px;color:var(--text);line-height:1.7;padding:6px 0;white-space:pre-wrap">{{ $timesheet->notes }}</div>
    </div>
    @endif

    @if($timesheet->assignedTimesheet)
    <div class="divider"></div>
    <div class="form-group" style="margin-bottom:0">
        <label>Linked Task</label>
        <a href="{{ route('assigned-timesheets.show', $timesheet->assignedTimesheet) }}" class="task-link" style="font-size:13px;color:var(--navy);text-decoration:none;font-weight:500">
            {{ $timesheet->assignedTimesheet->title }}
        </a>
        <div style="font-size:11px;color:var(--text3);margin-top:2px">Due {{ $timesheet->assignedTimesheet->due_date?->format('M j, Y') }}</div>
    </div>
    @endif
</div>

</x-app-layout>