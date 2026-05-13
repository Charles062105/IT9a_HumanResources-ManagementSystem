<x-app-layout title="Leaves" crumb="HR · Leave management">

<div class="page-header">
    <div>
        <div class="page-header-title">Leaves</div>
        <div class="page-header-sub">Manage and approve leave requests</div>
    </div>
    @if(auth()->user()->isEmployee())
    <a href="{{ route('leaves.create') }}" class="btn-primary" style="text-decoration:none">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        File Leave Request
    </a>
    @endif
</div>

<div class="section-card">
    <form method="GET" action="{{ route('leaves.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        @if(auth()->user()->isAdmin())
        <input class="finput" type="text" name="search" placeholder="Search employee name..." value="{{ request('search') }}">
        @endif
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
        <select class="fsel" name="month">
            <option value="">All months</option>
            @foreach(range(1, 12) as $m)
                @php $date = \Carbon\Carbon::createFromFormat('m', $m); @endphp
                <option value="{{ $date->format('Y-m') }}" {{ request('month') == $date->format('Y-m') ? 'selected' : '' }}>{{ $date->format('F') }}</option>
            @endforeach
        </select>
        <button type="submit" class="fbtn">Apply</button>
        @if(request()->anyFilled(['search','type','status','month']))
            <a href="{{ route('leaves.index') }}" class="fbtn ghost">Reset</a>
        @endif
        <span class="f-results">{{ $leaves->total() }} record{{ $leaves->total() !== 1 ? 's' : '' }}</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>Duration</th>
                    <th>Dates</th>
                    <th>Status</th>
                    <th>Approved By</th>
                    @if(auth()->user()->isAdmin())
                    <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $l)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="av" style="background:#DBEAFE;color:#1E40AF;font-size:10px">{{ $l->employee?->initials }}</div>
                            <span class="td-bold">{{ $l->employee?->full_name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ ucfirst(str_replace('_', ' ', $l->type)) }}</td>
                    <td class="td-muted">{{ $l->days }} day{{ $l->days != 1 ? 's' : '' }}</td>
                    <td class="td-muted">{{ $l->start_date?->format('M j') }} – {{ $l->end_date?->format('M j, Y') }}</td>
                    <td>
                        @php $sc = match($l->status) { 'approved' => 'sp-ok', 'denied' => 'sp-no', default => 'sp-pend' }; @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($l->status) }}</span>
                    </td>
                    <td class="td-muted">
                        @if($l->approver)
                            {{ $l->approver->name }}<br><span style="font-size:10px">{{ $l->approved_at?->format('M j, Y') }}</span>
                        @else
                            —
                        @endif
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div style="display:flex;gap:6px;align-items:center">
                            <a href="{{ route('leaves.show', $l) }}" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px;text-decoration:none">View</a>
                            @if($l->status === 'pending')
                                <button type="button" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px" onclick="openApproveModal({{ $l->id }}, '{{ addslashes($l->employee?->full_name) }}', '{{ $l->start_date?->format('M j') }} – {{ $l->end_date?->format('M j') }}')">Approve</button>
                                <button type="button" class="notif-action-btn notif-action-btn-delete" style="padding:3px 9px;font-size:10px;border-radius:5px" onclick="openDenyModal({{ $l->id }}, '{{ addslashes($l->employee?->full_name) }}', '{{ $l->start_date?->format('M j') }} – {{ $l->end_date?->format('M j') }}')">Deny</button>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="{{ auth()->user()->isAdmin() ? '8' : '7' }}"><div class="empty-state">No leave records found</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($leaves->hasPages())
        <div class="pagination-wrap">{{ $leaves->links() }}</div>
    @endif
</div>

{{-- Approve Modal --}}
<div id="approveModal" class="modal-overlay">
    <div class="modal-dialog" style="max-width:400px">
        <div class="modal-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            Approve Leave Request
        </div>
        <div class="modal-body">
            <p style="margin:0;font-size:13px;color:var(--text2);line-height:1.6">
                Approve <strong id="aprName" style="color:var(--text)"></strong>'s leave for <strong id="aprDates" style="color:var(--text)"></strong>?
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-deny" onclick="closeApproveModal()">Cancel</button>
            <form id="approveForm" method="POST" style="display:inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn-success" style="padding:8px 16px">Approve</button>
            </form>
        </div>
    </div>
</div>

{{-- Deny Modal --}}
<div id="denyModal" class="modal-overlay">
    <div class="modal-dialog" style="max-width:400px">
        <div class="modal-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Deny Leave Request
        </div>
        <div class="modal-body">
            <p style="margin:0;font-size:13px;color:var(--text2);line-height:1.6">
                Deny <strong id="denyName" style="color:var(--text)"></strong>'s leave for <strong id="denyDates" style="color:var(--text)"></strong>?
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-deny" onclick="closeDenyModal()">Cancel</button>
            <form id="denyForm" method="POST" style="display:inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn-danger" style="padding:8px 16px">Deny</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openApproveModal(id, name, dates) {
    document.getElementById('aprName').textContent = name;
    document.getElementById('aprDates').textContent = dates;
    var m = document.getElementById('approveModal');
    m.style.display = 'flex'; m.classList.add('show');
    document.getElementById('approveForm').action = '{{ route('leaves.approve', '__ID__') }}'.replace('__ID__', id);
}
function closeApproveModal() {
    var m = document.getElementById('approveModal');
    m.style.display = 'none'; m.classList.remove('show');
}

function openDenyModal(id, name, dates) {
    document.getElementById('denyName').textContent = name;
    document.getElementById('denyDates').textContent = dates;
    var m = document.getElementById('denyModal');
    m.style.display = 'flex'; m.classList.add('show');
    document.getElementById('denyForm').action = '{{ route('leaves.deny', '__ID__') }}'.replace('__ID__', id);
}
function closeDenyModal() {
    var m = document.getElementById('denyModal');
    m.style.display = 'none'; m.classList.remove('show');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeApproveModal(); closeDenyModal(); }
});
</script>
@endpush

</x-app-layout>