<x-app-layout title="Performance" crumb="HR · Performance reviews">

<div class="page-header">
    <div>
        <div class="page-header-title">Performance</div>
        <div class="page-header-sub">Employee performance reviews and ratings</div>
    </div>
    <div style="display:flex;gap:8px">
        @if(auth()->user()->isAdmin())
        <a href="{{ route('performance.create') }}" class="btn-primary" style="text-decoration:none">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline-block;vertical-align:middle;margin-right:4px"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Review
        </a>
        @endif
    </div>
</div>

<div class="section-card">
    <form method="GET" action="{{ route('performance.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <input class="finput" type="text" name="search" placeholder="Search employee..." value="{{ request('search') }}">
        <select class="fsel" name="department">
            <option value="">All departments</option>
            @foreach($departments as $d)
                <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        <select class="fsel" name="period">
            <option value="">All periods</option>
            @foreach($periods as $p)
                <option value="{{ $p }}" {{ request('period') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
        <select class="fsel" name="rating">
            <option value="">All ratings</option>
            @foreach(['Outstanding','Satisfactory','Needs Improvement','Poor'] as $r)
                <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>{{ $r }}</option>
            @endforeach
        </select>
        <button type="submit" class="fbtn">Apply</button>
        @if(request()->anyFilled(['search','department','period','rating']))
            <a href="{{ route('performance.index') }}" class="fbtn ghost">Reset</a>
        @endif
        <span class="f-results">{{ $records->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Period</th>
                    <th>Score</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                    <th>Reviewer</th>
                    <th>Date</th>
                    @if(auth()->user()->isAdmin())
                    <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="av" style="background:#DBEAFE;color:#1E40AF">{{ $r->employee?->initials }}</div>
                            <span class="td-bold">{{ $r->employee?->full_name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $r->employee?->department }}</td>
                    <td class="td-muted">{{ $r->period }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:7px">
                            <div class="p-bar"><div class="p-fill" style="width:{{ $r->score_pct ?? 0 }}%"></div></div>
                            <span class="td-bold">{{ $r->score }}</span>
                        </div>
                    </td>
                    <td>
                        @php
                        $sc = match($r->rating) {
                            'Outstanding'       => 'sp-ok',
                            'Satisfactory'      => 'sp-rev',
                            'Needs Improvement' => 'sp-late',
                            default             => 'sp-no',
                        };
                        @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ $r->rating }}</span>
                    </td>
                    <td class="td-muted" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $r->feedback ?? '' }}">{{ $r->feedback ?? '—' }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:5px">
                            <div class="av av-xs" style="background:#EDE9FE;color:#6D28D9;font-size:8px">{{ strtoupper(substr($r->reviewer?->name ?? '?', 0, 2)) }}</div>
                            <span class="td-muted">{{ $r->reviewer?->name ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $r->created_at->format('M j, Y') }}</td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('performance.edit', $r) }}" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px;text-decoration:none">Edit</a>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="{{ auth()->user()->isAdmin() ? '9' : '8' }}"><div class="empty-state">No performance records found</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
        <div class="pagination-wrap">{{ $records->links() }}</div>
    @endif
</div>


</x-app-layout>