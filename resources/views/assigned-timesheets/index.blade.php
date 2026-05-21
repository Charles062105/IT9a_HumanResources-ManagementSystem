<x-app-layout title="Assigned Tasks" crumb="HR · Task management">

<div class="page-header">
    <div>
        <div class="page-header-title">Assigned Tasks</div>
        <div class="page-header-sub">Admin-assigned work tasks for employees</div>
    </div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('assigned-timesheets.create') }}" class="btn-primary" style="text-decoration:none">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Assign Task
        </a>
    </div>
</div>

<div class="section-card">
    <form method="GET" action="{{ route('assigned-timesheets.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <input class="finput" type="text" name="search" placeholder="Search task title..." value="{{ request('search') }}">
        <select class="fsel" name="employee_id">
            <option value="">All employees</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
            @endforeach
        </select>
        <select class="fsel" name="status">
            <option value="">All status</option>
            @foreach(['pending','in_progress','submitted','approved','rejected'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="fbtn">Apply</button>
        @if(request()->anyFilled(['search','employee_id','status']))
            <a href="{{ route('assigned-timesheets.index') }}" class="fbtn ghost">Reset</a>
        @endif
        <span class="f-results">{{ $records->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Employee</th>
                    <th>Due Date</th>
                    <th style="text-align:right">Expected Hrs</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr>
                    <td class="td-bold">{{ $r->title }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="av av-sm" style="background:#DBEAFE;color:#1E40AF;font-size:9px">{{ $r->employee?->initials }}</div>
                            <span class="td-muted">{{ $r->employee?->full_name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $r->due_date?->format('M j, Y') }}</td>
                    <td style="text-align:right" class="td-muted">{{ $r->expected_hours }}h</td>
                    <td>
                        @php
                        $sc = match($r->status) {
                            'approved' => 'sp-ok',
                            'rejected' => 'sp-no',
                            'submitted' => 'sp-ok',
                            'in_progress' => 'sp-pend',
                            default => 'sp-pend',
                        };
                        @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst(str_replace('_',' ',$r->status)) }}</span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('assigned-timesheets.show', $r) }}" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px;text-decoration:none">View</a>
                            <a href="{{ route('assigned-timesheets.edit', $r) }}" class="notif-action-btn notif-action-btn-read" style="padding:3px 9px;font-size:10px;border-radius:5px;text-decoration:none">Edit</a>
                            <button type="button" class="notif-action-btn notif-action-btn-delete" style="padding:3px 9px;font-size:10px" onclick="confirmDelete({{ $r->id }}, '{{ addslashes($r->title) }}')">Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state">No assigned tasks found</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
        <div class="pagination-wrap">{{ $records->links() }}</div>
    @endif
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="delTitle" tabindex="-1">
    <div class="modal-dialog" style="max-width:400px">
        <div class="modal-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span id="delTitle">Delete Assigned Task</span>
            <button type="button" class="modal-close-btn" onclick="closeDeleteModal()" aria-label="Close modal">×</button>
        </div>
        <div class="modal-body">
            <p style="margin:0;font-size:13px;color:var(--text2);line-height:1.6">
                Are you sure you want to delete the task <strong id="delName" style="color:var(--text)"></strong>?<br><br>This action cannot be undone.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-deny" onclick="closeDeleteModal()">Cancel</button>
            <form id="deleteForm" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger" style="padding:8px 16px">Delete</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, name) {
    document.getElementById('delName').textContent = name || '—';
    var m = document.getElementById('deleteModal');
    m.classList.add('show');
    m.setAttribute('aria-hidden', 'false');
    document.getElementById('deleteForm').action = '{{ route('assigned-timesheets.destroy', '__ID__') }}'.replace('__ID__', id);
    m.querySelector('button[type="submit"]').focus();
}
function closeDeleteModal() {
    var m = document.getElementById('deleteModal');
    m.classList.remove('show');
    m.setAttribute('aria-hidden', 'true');
}
var delModal = document.getElementById('deleteModal');
if (delModal) {
    delModal.addEventListener('click', function(e) {
        if (e.target === delModal) closeDeleteModal();
    });
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>
@endpush

</x-app-layout>