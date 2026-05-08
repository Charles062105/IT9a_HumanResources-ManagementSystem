<x-app-layout title="Timesheets" crumb="HR · Timesheet management">

<div class="page-header">
    <div>
        <div class="page-header-title">Timesheets</div>
        <div class="page-header-sub">Weekly work hour logs and approvals</div>
    </div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('timesheets.create') }}" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:7px;font-size:12px">Submit Timesheet</a>
    </div>
</div>

<div class="section-card">
    <form method="GET" action="{{ route('timesheets.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <input class="finput" type="text" name="search" placeholder="Search employee..." value="{{ request('search') }}">
        <select class="fsel" name="department">
            <option value="">All departments</option>
            @foreach($departments as $d)
                <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        <select class="fsel" name="week">
            <option value="">All weeks</option>
            @foreach($weeks as $w)
                <option value="{{ $w }}" {{ request('week') == $w ? 'selected' : '' }}>{{ $w }}</option>
            @endforeach
        </select>
        <select class="fsel" name="status">
            <option value="">All status</option>
            @foreach(['pending','approved','rejected'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="fbtn">Apply</button>
        <a href="{{ route('timesheets.index') }}" class="fbtn ghost">Reset</a>
        <span class="f-results">{{ $records->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Week</th>
                    <th>Total Hrs</th>
                    <th>OT Hrs</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr>
                    <td class="td-bold">{{ $r->employee?->full_name }}</td>
                    <td class="td-muted">{{ $r->employee?->department }}</td>
                    <td class="td-muted">{{ $r->week_label }}</td>
                    <td class="td-bold">{{ $r->total_hours }}h</td>
                    <td class="td-muted">{{ $r->ot_hours ?? 0 }}h</td>
                    <td>
                        @php $sc = match($r->status) { 'approved' => 'sp-ok', 'rejected' => 'sp-no', default => 'sp-pend' }; @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($r->status) }}</span>
                    </td>
                    <td class="td-muted">{{ $r->submitted_at?->format('M j') }}</td>
                    <td>
                        @if(auth()->user()->isAdmin() && $r->status === 'pending')
                        <div style="display:flex;gap:5px">
                            <form method="POST" action="{{ route('timesheets.approve', $r) }}">@csrf @method('PATCH')
                                <button class="btn-approve">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('timesheets.reject', $r) }}">@csrf @method('PATCH')
                                <button class="btn-deny">Reject</button>
                            </form>
                        </div>
                        @else
                            <span class="td-muted">{{ ucfirst($r->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state">No timesheets found</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
        <div class="pagination-wrap">{{ $records->links() }}</div>
    @endif
</div>

</x-app-layout>
