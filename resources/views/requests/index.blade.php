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
        <a href="{{ route('requests.index') }}" class="fbtn ghost">Reset</a>
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
                            <div class="av" style="background:#DBEAFE;color:#1E40AF;width:28px;height:28px;font-size:10px">
                                {{ strtoupper(substr($req->user?->name ?? '?', 0, 2)) }}
                            </div>
                            <span class="td-bold">{{ $req->user?->name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $req->user?->email }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px">
                            {{ $req->type }}
                            @if($req->type === 'Account Activation')
                            <span style="font-size:10px;background:#EDE9FE;color:#5B21B6;padding:1px 6px;border-radius:4px;font-weight:500">
                                → creates profile
                            </span>
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
                        <div style="display:flex;gap:5px;align-items:center">
                            <form method="POST" action="{{ route('requests.approve', $req) }}">
                                @csrf @method('PATCH')
                                <button class="btn-approve" title="{{ $req->type === 'Account Activation' ? 'Approve and fill in employee profile' : 'Approve request' }}">
                                    Approve{{ $req->type === 'Account Activation' ? ' & setup' : '' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('requests.reject', $req) }}">
                                @csrf @method('PATCH')
                                <button class="btn-deny">Reject</button>
                            </form>
                        </div>
                        @if($req->type === 'Account Activation')
                        <div style="font-size:10px;color:var(--text3);margin-top:3px">
                            Approving opens the employee profile form
                        </div>
                        @endif
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

</x-app-layout>
