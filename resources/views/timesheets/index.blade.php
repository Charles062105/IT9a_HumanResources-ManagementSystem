<x-app-layout title="Timesheets" crumb="HR · Timesheet management">

<div class="page-header">
    <div>
        <div class="page-header-title">Timesheets</div>
        <div class="page-header-sub">Weekly work hour logs and approvals</div>
    </div>
            <div style="display:flex;gap:8px">
        @if(auth()->user()->employee)
        <a href="{{ route('timesheets.create') }}" class="btn-primary" style="text-decoration:none">

            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Submit Timesheet
        </a>
        @endif
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
        @if(request()->anyFilled(['search','department','week','status']))
            <a href="{{ route('timesheets.index') }}" class="fbtn ghost">Reset</a>
        @endif
        <span class="f-results">{{ $records->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Week</th>
                    <th style="text-align:right">Total Hrs</th>
                    <th style="text-align:right">OT Hrs</th>
                    <th>Status</th>
                    <th>Submitted</th>
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
                    <td class="td-muted">{{ $r->week_label }}</td>
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
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div class="table-actions">
                                <a href="{{ route('timesheets.show', $r) }}" class="notif-action-btn notif-action-btn-read">View</a>

                            @if($r->status === 'pending')
                                <button type="button" class="notif-action-btn notif-action-btn-read" onclick="openApproveModal({{ $r->id }}, @js($r->employee?->full_name), @js($r->week_label))">Approve</button>
                                <button type="button" class="notif-action-btn notif-action-btn-read" onclick="openRejectModal({{ $r->id }}, @js($r->employee?->full_name), @js($r->week_label))">Reject</button>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="{{ auth()->user()->isAdmin() ? '9' : '8' }}"><div class="empty-state">No timesheets found</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
        <div class="pagination-wrap">{{ $records->links() }}</div>
    @endif
</div>

{{-- Approve Confirmation Modal --}}
<div id="approveModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="approveModalTitle" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog" style="max-width:400px" role="document">
        <div class="modal-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <h3 id="approveModalTitle" style="margin:0">Approve Timesheet</h3>
        </div>
        <div class="modal-body">
            <p style="margin:0;font-size:13px;color:var(--text2);line-height:1.6">
                Approve <strong id="aprName" style="color:var(--text)"></strong>'s timesheet for <strong id="aprWeek" style="color:var(--text)"></strong>?
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-deny" onclick="closeApproveModal()">Cancel</button>
            <form id="approveForm" method="POST" style="display:inline" data-approve-action="{{ route('timesheets.approve', '__ID__') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-success" style="padding:8px 16px">Approve</button>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal with Reason --}}
<div id="rejectModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="rejectModalTitle" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog" style="max-width:420px">
        <div class="modal-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <h3 id="rejectModalTitle" style="margin:0">Reject Timesheet</h3>
        </div>
        <div class="modal-body">
            <p style="margin:0 0 12px;font-size:13px;color:var(--text2);line-height:1.6">
                Reject <strong id="rejName" style="color:var(--text)"></strong>'s timesheet for <strong id="rejWeek" style="color:var(--text)"></strong>?
            </p>
            <label style="font-size:12px;font-weight:500;color:var(--text);display:block;margin-bottom:6px">Reason (optional)</label>
            <textarea id="rejectReason" name="reason" rows="2" placeholder="Provide a reason for rejection..." style="width:100%;border:1px solid var(--border2);border-radius:6px;padding:8px;font-size:12px;color:var(--text);resize:none;outline:none;font-family:inherit"></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-deny" onclick="closeRejectModal()">Cancel</button>
            <form id="rejectForm" method="POST" style="display:inline" data-reject-action="{{ route('timesheets.reject', '__ID__') }}">
                @csrf
                @method('PATCH')
                <input type="hidden" id="rejectReasonInput" name="reason" value="">
                <button type="submit" class="btn-danger" style="padding:8px 16px">Reject</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openApproveModal(id, name, week) {
    document.getElementById('aprName').textContent = name;
    document.getElementById('aprWeek').textContent = week;

    var m = document.getElementById('approveModal');
    m.classList.add('show');
    m.setAttribute('aria-hidden', 'false');

    // Avoid VSCode false-positive by not using a fake route parameter placeholder like __ID__.
    // Use the route URL built from the current route helper output and replace the numeric id.
document.getElementById('approveForm').action = document.getElementById('approveForm').dataset.approveAction.replace('__ID__', id);

    // Focus first button for accessibility
    var primary = m.querySelector('button[type="submit"], button');
    if (primary) primary.focus();
}
function closeApproveModal() {
    var m = document.getElementById('approveModal');
    m.classList.remove('show');
    m.setAttribute('aria-hidden', 'true');
}

function openRejectModal(id, name, week) {
    document.getElementById('rejName').textContent = name;
    document.getElementById('rejWeek').textContent = week;
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectReasonInput').value = '';

    var m = document.getElementById('rejectModal');
    m.classList.add('show');
    m.setAttribute('aria-hidden', 'false');

document.getElementById('rejectForm').action = document.getElementById('rejectForm').dataset.rejectAction.replace('__ID__', id);

    var first = m.querySelector('button[type="submit"], textarea, button');
    if (first) first.focus();
}
function closeRejectModal() {
    var m = document.getElementById('rejectModal');
    m.classList.remove('show');
    m.setAttribute('aria-hidden', 'true');
}

// Wire reject reason textarea to hidden input
document.getElementById('rejectForm').addEventListener('submit', function() {
    document.getElementById('rejectReasonInput').value = document.getElementById('rejectReason').value;
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApproveModal(); closeRejectModal();
    }
});
</script>
@endpush

</x-app-layout>