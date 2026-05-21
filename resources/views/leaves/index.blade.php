<x-app-layout title="Leaves" crumb="HR · Leave management">

    <div class="page-header">
        <div>
            <div class="page-header-title">Leaves</div>
            <div class="page-header-sub">Manage and approve leave requests</div>
        </div>
        @if(auth()->user()->isEmployee())
            <a href="{{ route('leaves.create') }}" class="btn-primary" style="text-decoration:none">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                File Leave Request
            </a>
        @endif
    </div>

    @if(auth()->user()->isAdmin() && !empty($kpis))
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(140px, 1fr));gap:12px;margin-bottom:16px">
            <div style="background:var(--card);border:1px solid var(--border);border-radius:8px;padding:16px;text-align:center">
                    <div style="font-size:11px;font-weight:500;color:var(--text3);margin-bottom:6px">Pending</div>
                    <div style="font-size:24px;font-weight:700;color:{{ $kpis['pending'] > 0 ? 'var(--warn)' : 'var(--text)' }}">{{ $kpis['pending'] }}</div>
                </div>
            <div style="background:var(--card);border:1px solid var(--border);border-radius:8px;padding:16px;text-align:center">
                    <div style="font-size:11px;font-weight:500;color:var(--text3);margin-bottom:6px">Approved</div>
                    <div style="font-size:24px;font-weight:700;color:var(--success)">{{ $kpis['approved'] }}</div>
                </div>
            <div style="background:var(--card);border:1px solid var(--border);border-radius:8px;padding:16px;text-align:center">
                    <div style="font-size:11px;font-weight:500;color:var(--text3);margin-bottom:6px">Denied</div>
                    <div style="font-size:24px;font-weight:700;color:var(--danger)">{{ $kpis['denied'] }}</div>
                </div>
            <div style="background:var(--card);border:1px solid var(--border);border-radius:8px;padding:16px;text-align:center">
                    <div style="font-size:11px;font-weight:500;color:var(--text3);margin-bottom:6px">Total</div>
                    <div style="font-size:24px;font-weight:700;color:var(--text)">{{ $kpis['total'] }}</div>
                </div>
        </div>
    @endif

    <div class="section-card">
        <form method="GET" action="{{ route('leaves.index') }}" class="filter-bar" style="flex-wrap:wrap;gap:8px">
            @if(auth()->user()->isAdmin())
                <input class="finput" type="text" name="search" placeholder="Search employee..."
                    value="{{ request('search') }}" style="flex:1;min-width:150px">
            @endif
            <select class="fsel" name="type" style="min-width:100px">
                <option value="">All types</option>
                @foreach(['vacation', 'sick', 'emergency', 'maternity', 'paternity', 'solo_parent'] as $t)
                    <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $t)) }}</option>
                @endforeach
            </select>
            <select class="fsel" name="status">
                <option value="">All status</option>
                @foreach(['pending', 'approved', 'denied'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select class="fsel" name="month">
                <option value="">All months</option>
                @foreach(range(1, 12) as $m)
                    @php $date = \Carbon\Carbon::createFromFormat('m', $m); @endphp
                    <option value="{{ $date->format('Y-m') }}" {{ request('month') == $date->format('Y-m') ? 'selected' : '' }}>{{ $date->format('F Y') }}</option>
                @endforeach
            </select>
            <button type="submit" class="fbtn">Apply</button>
            @if(request()->anyFilled(['search', 'type', 'status', 'month']))
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
                                    <div class="av" style="background:#DBEAFE;color:#1E40AF;font-size:10px">
                                        {{ $l->employee?->initials }}</div>
                                    <span class="td-bold">{{ $l->employee?->full_name }}</span>
                                </div>
                            </td>
                            <td class="td-muted">{{ ucfirst(str_replace('_', ' ', $l->type)) }}</td>
                            <td class="td-muted">{{ $l->days }} day{{ $l->days != 1 ? 's' : '' }}</td>
                            <td class="td-muted">{{ $l->start_date?->format('M j') }} –
                                {{ $l->end_date?->format('M j, Y') }}</td>
                            <td>
                                @php $sc = match ($l->status) { 'approved' => 'sp-ok', 'denied' => 'sp-no', default => 'sp-pend'}; @endphp
                                <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($l->status) }}</span>
                            </td>
                            <td class="td-muted">
                                @if($l->approver)
                                    {{ $l->approver->name }}<br><span
                                        style="font-size:10px">{{ $l->approved_at?->format('M j, Y') }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            @if(auth()->user()->isAdmin())
                                <td>
                                    <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                                        <a href="{{ route('leaves.show', ['leaf' => $l->id]) }}"
                                            class="btn-secondary"
                                            style="padding:4px 10px;font-size:11px;border-radius:5px;text-decoration:none">View</a>
                                        @if($l->status === 'pending')
                                            <button type="button" class="btn-success"
                                                style="padding:4px 10px;font-size:11px;border-radius:5px"
                                                onclick="openApproveModal({{ $l->id }}, '{{ addslashes($l->employee?->full_name) }}', '{{ $l->start_date?->format('M j') }} – {{ $l->end_date?->format('M j') }}')">Approve</button>
                                            <button type="button" class="btn-danger"
                                                style="padding:4px 10px;font-size:11px;border-radius:5px"
                                                onclick="openDenyModal({{ $l->id }}, '{{ addslashes($l->employee?->full_name) }}', '{{ $l->start_date?->format('M j') }} – {{ $l->end_date?->format('M j') }}')">Deny</button>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? '7' : '6' }}">
                                <div class="empty-state" style="padding:32px 24px">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                    </svg>
                                    No leave records found
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leaves->hasPages())
            <div class="pagination-wrap">{{ $leaves->links() }}</div>
        @endif
    </div>

    {{-- Approve Modal --}}
    <div id="approveModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="aprTitle"
        tabindex="-1">
        <div class="modal-dialog" style="max-width:400px">
            <div class="modal-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <span id="aprTitle">Approve Leave Request</span>
                <button type="button" class="modal-close-btn" onclick="closeApproveModal()"
                    aria-label="Close modal">×</button>
            </div>
            <div class="modal-body">
                <p style="margin:0;font-size:13px;color:var(--text2);line-height:1.6">
                    Approve <strong id="aprName" style="color:var(--text)"></strong>'s leave for <strong id="aprDates"
                        style="color:var(--text)"></strong>?
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
    <div id="denyModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="denyTitle" tabindex="-1">
        <div class="modal-dialog" style="max-width:400px">
            <div class="modal-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                </svg>
                <span id="denyTitle">Deny Leave Request</span>
                <button type="button" class="modal-close-btn" onclick="closeDenyModal()"
                    aria-label="Close modal">×</button>
            </div>
            <div class="modal-body">
                <p style="margin:0;font-size:13px;color:var(--text2);line-height:1.6">
                    Deny <strong id="denyName" style="color:var(--text)"></strong>'s leave for <strong id="denyDates"
                        style="color:var(--text)"></strong>?
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
                m.classList.add('show');
                m.setAttribute('aria-hidden', 'false');
                document.getElementById('approveForm').action = '{{ route('leaves.approve', '__ID__') }}'.replace('__ID__', id);
                m.querySelector('button[type="submit"]').focus();
            }
            function closeApproveModal() {
                var m = document.getElementById('approveModal');
                m.classList.remove('show');
                m.setAttribute('aria-hidden', 'true');
            }

            function openDenyModal(id, name, dates) {
                document.getElementById('denyName').textContent = name;
                document.getElementById('denyDates').textContent = dates;
                var m = document.getElementById('denyModal');
                m.classList.add('show');
                m.setAttribute('aria-hidden', 'false');
                document.getElementById('denyForm').action = '{{ route('leaves.deny', '__ID__') }}'.replace('__ID__', id);
                m.querySelector('button[type="submit"]').focus();
            }
            function closeDenyModal() {
                var m = document.getElementById('denyModal');
                m.classList.remove('show');
                m.setAttribute('aria-hidden', 'true');
            }

            // Close modals on backdrop click
            ['approveModal', 'denyModal'].forEach(function (id) {
                var m = document.getElementById(id);
                if (!m) return;
                m.addEventListener('click', function (e) {
                    if (e.target === m) {
                        if (id === 'approveModal') closeApproveModal();
                        else closeDenyModal();
                    }
                });
            });

            // Close modals on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { closeApproveModal(); closeDenyModal(); }
            });
        </script>
    @endpush

</x-app-layout>