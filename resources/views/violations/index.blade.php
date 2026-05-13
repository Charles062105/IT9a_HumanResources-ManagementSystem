<x-app-layout title="Violations" crumb="HR · Disciplinary tracker">

<div class="page-header">
    <div>
        <div class="page-header-title">Violations</div>
        <div class="page-header-sub">Progressive discipline records — 5 levels: Verbal → Written → Final → Suspension → Termination</div>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('violations.create') }}" class="btn-primary" style="text-decoration:none">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Log Violation
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
        @if(request()->anyFilled(['search','level','department','status']))
            <a href="{{ route('violations.index') }}" class="fbtn ghost">Reset</a>
        @endif
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
                    <th style="text-align:center">#</th>
                    <th>Status</th>
                    <th>Date</th>
                    @if(auth()->user()->isAdmin())
                    <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($violations as $v)
                @php $badge = $v->level_badge_color; @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="av av-sm" style="background:#FEE2E2;color:#991B1B;font-size:9px">{{ $v->employee?->initials }}</div>
                            <span class="td-bold">{{ $v->employee?->full_name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $v->employee?->department }}</td>
                    <td>
                        <span class="sp" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border-radius:5px">{{ $v->level }}</span>
                    </td>
                    <td class="td-muted" style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $v->offense }}">{{ $v->offense }}</td>
                    <td style="text-align:center">
                        <span class="vb" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }}">{{ $v->offense_count }}</span>
                    </td>
                    <td>
                        @php $sc = $v->status === 'open' ? 'sp-open' : 'sp-ok'; @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($v->status) }}</span>
                    </td>
                    <td class="td-muted">{{ $v->date?->format('M j, Y') }}</td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div style="display:flex;gap:6px;align-items:center">
                            <a href="{{ route('violations.show', $v) }}" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px;text-decoration:none">View</a>
                            @if($v->status === 'open')
                                <button type="button" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px" onclick="openResolveModal({{ $v->id }}, '{{ addslashes($v->employee?->full_name) }}', '{{ addslashes($v->offense) }}')">Resolve</button>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="{{ auth()->user()->isAdmin() ? '9' : '8' }}"><div class="empty-state">
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

{{-- Resolve Modal --}}
<div id="resolveModal" class="modal-overlay">
    <div class="modal-dialog" style="max-width:400px">
        <div class="modal-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            Mark as Resolved
        </div>
        <div class="modal-body">
            <p style="margin:0;font-size:13px;color:var(--text2);line-height:1.6">
                Mark <strong id="resName" style="color:var(--text)"></strong>'s violation<br>
                "<em id="resOffense" style="color:var(--text)"></em>" as resolved?
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-deny" onclick="closeResolveModal()">Cancel</button>
            <form id="resolveForm" method="POST" style="display:inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn-success" style="padding:8px 16px">Resolve</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openResolveModal(id, name, offense) {
    document.getElementById('resName').textContent = name;
    document.getElementById('resOffense').textContent = offense;
    var m = document.getElementById('resolveModal');
    m.style.display = 'flex'; m.classList.add('show');
    document.getElementById('resolveForm').action = '{{ route('violations.resolve', '__ID__') }}'.replace('__ID__', id);
}
function closeResolveModal() {
    var m = document.getElementById('resolveModal');
    m.style.display = 'none'; m.classList.remove('show');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeResolveModal(); }
});
</script>
@endpush

</x-app-layout>