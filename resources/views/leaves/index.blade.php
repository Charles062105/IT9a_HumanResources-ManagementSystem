<x-app-layout title="Leaves" crumb="HR · Leave management">

<div class="page-header">
    <div>
        <div class="page-header-title">Leaves</div>
        <div class="page-header-sub">Manage and approve leave requests</div>
    </div>
    <a href="{{ route('leaves.create') }}" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:7px;font-size:12px">
        + File Leave
    </a>
</div>

<div class="section-card">
    <form method="GET" action="{{ route('leaves.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <input class="finput" type="text" name="search" placeholder="Search employee..." value="{{ request('search') }}">
        <select class="fsel" name="type">
            <option value="">All types</option>
            @foreach(['vacation','sick','emergency','maternity','paternity','solo_parent'] as $t)
                <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
            @endforeach
        </select>
        <select class="fsel" name="status">
            <option value="">All status</option>
            @foreach(['pending','approved','denied'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="fbtn">Apply</button>
        <a href="{{ route('leaves.index') }}" class="fbtn ghost">Reset</a>
        <span class="f-results">{{ $leaves->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>Duration</th>
                    <th>Dates</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Filed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $l)
                <tr>
                    <td class="td-bold">{{ $l->employee?->full_name }}</td>
                    <td class="td-muted">{{ ucfirst($l->type) }}</td>
                    <td class="td-muted">{{ $l->days }} day{{ $l->days != 1 ? 's' : '' }}</td>
                    <td class="td-muted">{{ $l->start_date->format('M j') }}–{{ $l->end_date->format('M j, Y') }}</td>
                    <td class="td-muted" style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $l->reason ?? '—' }}</td>
                    <td>
                        @php $sc = match($l->status) { 'approved' => 'sp-ok', 'denied' => 'sp-no', default => 'sp-pend' }; @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($l->status) }}</span>
                    </td>
                    <td class="td-muted">{{ $l->created_at->format('M j') }}</td>
                    <td>
                        <span class="td-muted">{{ ucfirst($l->status) }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state">No leave records found</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($leaves->hasPages())
        <div class="pagination-wrap">{{ $leaves->links() }}</div>
    @endif
</div>

</x-app-layout>
