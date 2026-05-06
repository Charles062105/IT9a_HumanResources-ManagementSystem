<x-app-layout title="Violations" crumb="HR · Disciplinary tracker">

<div class="page-header">
    <div>
        <div class="page-header-title">Violations</div>
        <div class="page-header-sub">Progressive discipline records — 5 levels: Verbal → Written → Final → Suspension → Termination</div>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('violations.create') }}" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:7px;font-size:12px">
        + Log Violation
    </a>
    @endif
</div>

<div class="section-card">
    <form method="GET" action="{{ route('violations.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <input class="finput" type="text" name="search" placeholder="Search employee..." value="{{ request('search') }}">
        <select class="fsel" name="level">
            <option value="">All levels</option>
            @foreach(['Verbal Warning','Written Warning','Final Warning','Suspension','Termination'] as $l)
                <option value="{{ $l }}" {{ request('level') == $l ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        <select class="fsel" name="department">
            <option value="">All departments</option>
            @foreach($departments as $d)
                <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        <select class="fsel" name="status">
            <option value="">All status</option>
            <option value="open"     {{ request('status') == 'open'     ? 'selected' : '' }}>Open</option>
            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
        </select>
        <button type="submit" class="fbtn">Apply</button>
        <a href="{{ route('violations.index') }}" class="fbtn ghost">Reset</a>
        <span class="f-results">{{ $violations->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Level</th>
                    <th>Offense</th>
                    <th>#</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($violations as $v)
                @php $badge = $v->level_badge_color; @endphp
                <tr>
                    <td class="td-bold">{{ $v->employee?->full_name }}</td>
                    <td class="td-muted">{{ $v->employee?->department }}</td>
                    <td>
                        <span class="sp" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border-radius:5px">
                            {{ $v->level }}
                        </span>
                    </td>
                    <td class="td-muted">{{ $v->offense }}</td>
                    <td style="text-align:center">
                        <span class="vb" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }}">{{ $v->offense_count }}</span>
                    </td>
                    <td>
                        @php $sc = $v->status === 'open' ? 'sp-open' : 'sp-ok'; @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($v->status) }}</span>
                    </td>
                    <td class="td-muted">{{ $v->date?->format('M j, Y') }}</td>
                    <td>
                        @if(auth()->user()->isAdmin())
                        <div style="display:flex;gap:6px;align-items:center">
                            <a href="{{ route('violations.show', $v) }}" class="page-link-text">View</a>
                            @if($v->status === 'open')
                            <form method="POST" action="{{ route('violations.resolve', $v) }}">@csrf @method('PATCH')
                                <button type="submit" style="background:none;border:none;font-size:11px;font-weight:500;color:var(--success);cursor:pointer;font-family:inherit;padding:0">Resolve</button>
                            </form>
                            @endif
                        </div>
                        @else
                            <span class="td-muted">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    No violations found
                </div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($violations->hasPages())
        <div class="pagination-wrap">{{ $violations->links() }}</div>
    @endif
</div>

</x-app-layout>
