<x-app-layout title="{{ $assignedTimesheet->title }}" crumb="HR · Tasks · Show">

<div class="page-header">
    <div>
        <div class="page-header-title">{{ $assignedTimesheet->title }}</div>
        <div class="page-header-sub">Assigned to {{ $assignedTimesheet->employee?->full_name }}</div>
    </div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('assigned-timesheets.edit', $assignedTimesheet) }}" class="btn-primary" style="text-decoration:none">Edit</a>
        <a href="{{ route('assigned-timesheets.index') }}" class="btn-secondary">← Back</a>
    </div>
</div>

<div class="section-card">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px">
        <div>
            <div style="font-size:12px;color:var(--text3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">Employee</div>
            <div style="font-weight:600">{{ $assignedTimesheet->employee?->full_name }}</div>
        </div>
        <div>
            <div style="font-size:12px;color:var(--text3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">Status</div>
            @php $sc = match($assignedTimesheet->status) { 'approved' => 'sp-ok', 'rejected' => 'sp-no', 'submitted' => 'sp-ok', 'in_progress' => 'sp-pend', default => 'sp-pend' }; @endphp
            <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst(str_replace('_',' ',$assignedTimesheet->status)) }}</span>
        </div>
        <div>
            <div style="font-size:12px;color:var(--text3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">Due Date</div>
            <div style="font-weight:600">{{ $assignedTimesheet->due_date?->format('M j, Y') }}</div>
        </div>
        <div>
            <div style="font-size:12px;color:var(--text3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">Expected Hours</div>
            <div style="font-weight:600">{{ $assignedTimesheet->expected_hours }}h</div>
        </div>
    </div>

    @if($assignedTimesheet->description)
    <div style="margin-bottom:24px">
        <div style="font-size:12px;color:var(--text3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">Description</div>
        <div style="background:#f9fafb;border-radius:8px;padding:12px">{{ $assignedTimesheet->description }}</div>
    </div>
    @endif

    @if($assignedTimesheet->admin_notes)
    <div style="margin-bottom:24px">
        <div style="font-size:12px;color:var(--text3);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">Admin Notes</div>
        <div style="background:#fef3c7;border-radius:8px;padding:12px">{{ $assignedTimesheet->admin_notes }}</div>
    </div>
    @endif

    <div>
        <div style="font-size:14px;font-weight:600;margin-bottom:12px">Linked Timesheet Submissions ({{ $assignedTimesheet->timesheets->count() }})</div>
        @if($assignedTimesheet->timesheets->count())
            <table>
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Total Hrs</th>
                        <th>OT Hrs</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignedTimesheet->timesheets as $ts)
                    <tr>
                        <td class="td-muted">{{ $ts->week_label }}</td>
                        <td class="td-bold">{{ $ts->total_hours }}h</td>
                        <td class="td-muted">{{ $ts->ot_hours ?? 0 }}h</td>
                        <td>
                            @php $sc = match($ts->status) { 'approved' => 'sp-ok', 'rejected' => 'sp-no', default => 'sp-pend' }; @endphp
                            <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($ts->status) }}</span>
                        </td>
                        <td class="td-muted">{{ $ts->submitted_at?->format('M j') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">No timesheet submissions linked yet</div>
        @endif
    </div>
</div>

</x-app-layout>
