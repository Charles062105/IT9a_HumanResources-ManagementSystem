<x-app-layout title="My Timesheets" crumb="HR · Timesheets · Mine">

<div class="page-header">
    <div>
        <div class="page-header-title">My Timesheets</div>
        <div class="page-header-sub">Your submitted weekly hours</div>
    </div>
    <a href="{{ route('timesheets.create') }}" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:7px;font-size:12px">
        + Submit New
    </a>
</div>

<div class="section-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Week</th>
                    <th>Total Hours</th>
                    <th>OT Hours</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr>
                    <td class="td-bold">{{ $r->week_label }}</td>
                    <td class="td-bold">{{ $r->total_hours }}h</td>
                    <td class="td-muted">{{ $r->ot_hours ?? 0 }}h</td>
                    <td>
                        @php $sc = match($r->status) { 'approved' => 'sp-ok', 'rejected' => 'sp-no', default => 'sp-pend' }; @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($r->status) }}</span>
                    </td>
                    <td class="td-muted">{{ $r->submitted_at?->format('M j, Y') }}</td>
                    <td class="td-muted">{{ $r->notes ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            No timesheets submitted yet
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

</x-app-layout>
