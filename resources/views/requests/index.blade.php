<x-app-layout title="Requests" crumb="Admin · Pending approvals">

<div class="page-header">
    <div>
        <div class="page-header-title">Requests</div>
        <div class="page-header-sub">User account and access requests awaiting action</div>
    </div>
</div>

<div class="section-card">
    <form method="GET" action="{{ route('requests.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <input class="finput" type="text" name="search" placeholder="Search user..." value="{{ request('search') }}">
        <select class="fsel" name="type">
            <option value="">All request types</option>
            @foreach(['Account Activation','Role Change','Profile Update','Password Reset'] as $t)
                <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <select class="fsel" name="status">
            <option value="">All status</option>
            @foreach(['pending','approved','rejected'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="fbtn">Apply</button>
        @if(request()->anyFilled(['search','type','status']))
            <a href="{{ route('requests.index') }}" class="fbtn ghost">Reset</a>
        @endif
        <span class="f-results">{{ $requests->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Request type</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="av av-sm av-info">{{ strtoupper(substr($req->user?->name ?? '?', 0, 2)) }}</div>
                            <span class="td-bold">{{ $req->user?->name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $req->user?->email }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px">
                            {{ $req->type }}
                            @if($req->type === 'Account Activation')
                            <span style="font-size:10px;background:var(--purple-lt);color:var(--purple);padding:1px 6px;border-radius:4px;font-weight:500">→ profile</span>
                            @endif
                        </div>
                    </td>
                    <td class="td-muted">{{ $req->details ?? '—' }}</td>
                    <td>
                        @php $sc = match($req->status) { 'approved' => 'sp-ok', 'rejected' => 'sp-no', default => 'sp-pend' }; @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($req->status) }}</span>
                    </td>
                    <td class="td-muted">{{ $req->created_at->format('M j, Y') }}</td>
                    <td>
                        @if($req->status === 'pending')
                        <div class="table-actions">
                            @if($req->type === 'Account Activation')
                                <button type="button" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px" onclick="openApproveModal({{ $req->id }}, @js($req->user?->name))">Approve & setup</button>
                            @else
                                <button type="button" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px" onclick="openApproveModal({{ $req->id }}, @js($req->user?->name))">Approve</button>
                            @endif
                            <button type="button" class="notif-action-btn notif-action-btn-delete" style="padding:3px 9px;font-size:10px;border-radius:5px" onclick="openRejectModal({{ $req->id }}, @js($req->user?->name))">Reject</button>
                        </div>
                        @else
                        <span class="td-muted" style="font-size:11px">
                            {{ ucfirst($req->status) }}
                            @if($req->resolved_at) · {{ $req->resolved_at->format('M j') }} @endif
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                            No requests found
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requests->hasPages())
        <div class="pagination-wrap">{{ $requests->links() }}</div>
    @endif
</div>

{{-- Approve Modal --}}
<div id="approveModal" class="modal-overlay">
    <div class="modal-dialog" style="max-width:400px">
        <div class="modal-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            Approve Request
        </div>
        <div class="modal-body">
            <p style="margin:0;font-size:13px;color:var(--text2);line-height:1.6">
                Approve <strong id="aprName" style="color:var(--text)"></strong>'s request?<br>
                <span id="aprNote" style="font-size:12px;color:var(--text3)"></span>
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

{{-- Reject Modal --}}
<div id="rejectModal" class="modal-overlay">
    <div class="modal-dialog" style="max-width:400px">
        <div class="modal-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Reject Request
        </div>
        <div class="modal-body">
            <p style="margin:0;font-size:13px;color:var(--text2);line-height:1.6">
                Reject <strong id="rejName" style="color:var(--text)"></strong>'s request?
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-deny" onclick="closeRejectModal()">Cancel</button>
            <form id="rejectForm" method="POST" style="display:inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn-danger" style="padding:8px 16px">Reject</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
var requestTypes = {!! json_encode($requests->pluck('type', 'id')) !!};

function openApproveModal(id, name) {
    document.getElementById('aprName').textContent = name;
    var noteEl = document.getElementById('aprNote');
    var reqType = requestTypes[id];
    noteEl.textContent = reqType === 'Account Activation'
        ? 'Approving will open the employee profile setup form.'
        : '';
    var m = document.getElementById('approveModal');
    m.style.display = 'flex'; m.classList.add('show');
    document.getElementById('approveForm').action = '{{ route('requests.approve', '__ID__') }}'.replace('__ID__', id);
}
function closeApproveModal() {
    var m = document.getElementById('approveModal');
    m.style.display = 'none'; m.classList.remove('show');
}

function openRejectModal(id, name) {
    document.getElementById('rejName').textContent = name;
    var m = document.getElementById('rejectModal');
    m.style.display = 'flex'; m.classList.add('show');
    document.getElementById('rejectForm').action = '{{ route('requests.reject', '__ID__') }}'.replace('__ID__', id);
}
function closeRejectModal() {
    var m = document.getElementById('rejectModal');
    m.style.display = 'none'; m.classList.remove('show');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeApproveModal(); closeRejectModal(); }
});
</script>
@endpush

</x-app-layout>