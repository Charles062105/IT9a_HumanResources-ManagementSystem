<x-app-layout title="My Timesheets" crumb="HR · Timesheets · Mine">

<div class="page-header">
    <div>
        <div class="page-header-title">My Timesheets</div>
        <div class="page-header-sub">Your submitted weekly hours</div>
    </div>
    <a href="{{ route('timesheets.create') }}" class="btn-primary" style="text-decoration:none">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Submit New
    </a>
</div>

<div class="section-card">
    <form method="GET" action="{{ route('timesheets.my') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <select class="fsel" name="status">
            <option value="">All status</option>
            @foreach(['pending','approved','rejected'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select class="fsel" name="week">
            <option value="">All weeks</option>
            @foreach($weeks as $w)
                <option value="{{ $w }}" {{ request('week') == $w ? 'selected' : '' }}>{{ $w }}</option>
            @endforeach
        </select>
        <button type="submit" class="fbtn">Apply</button>
        @if(request()->anyFilled(['status','week']))
            <a href="{{ route('timesheets.my') }}" class="fbtn ghost">Reset</a>
        @endif
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Week</th>
                    <th style="text-align:right">Total Hrs</th>
                    <th style="text-align:right">OT Hrs</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Notes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr>
                    <td class="td-bold">{{ $r->week_label }}</td>
                    <td style="text-align:right;font-weight:600;font-size:12px">{{ $r->total_hours }}h</td>
                    <td style="text-align:right" class="td-muted">{{ $r->ot_hours ? "{$r->ot_hours}h" : '—' }}</td>
                    <td>
                        @php
                        $sc = match($r->status) {
                            'approved' => 'sp-ok',
                            'rejected' => 'sp-no',
                            default => 'sp-pend',
                        };
                        @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($r->status) }}</span>
                    </td>
                    <td class="td-muted">{{ $r->submitted_at?->format('M j, Y') }}</td>
                    <td class="td-muted" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $r->notes ?? '' }}">{{ $r->notes ?? '—' }}</td>
                    <td>
                        <a href="{{ route('timesheets.show', $r) }}" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px;text-decoration:none">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
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