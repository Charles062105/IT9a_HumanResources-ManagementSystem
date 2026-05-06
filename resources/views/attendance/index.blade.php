<x-app-layout title="Attendance" crumb="HR · Attendance tracking">

<div class="page-header">
    <div>
        <div class="page-header-title">Attendance</div>
        <div class="page-header-sub">Daily attendance records and time tracking</div>
    </div>
    @if(auth()->user()->employee)
    <div style="display:flex;gap:8px">
        <form method="POST" action="{{ route('attendance.time-in') }}">
            @csrf
            <button class="btn-primary" type="submit">⏱ Time In</button>
        </form>
        <form method="POST" action="{{ route('attendance.time-out') }}">
            @csrf
            <button class="btn-primary" type="submit" style="background:var(--success)">✓ Time Out</button>
        </form>
    </div>
    @endif
</div>

<div class="section-card">
    <form method="GET" action="{{ route('attendance.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <input class="finput" type="text" name="search" placeholder="Search employee..." value="{{ request('search') }}">
        <input class="finput" type="date" name="date" value="{{ request('date') }}" style="width:140px" title="Filter by date">
        <select class="fsel" name="department">
            <option value="">All departments</option>
            @foreach($departments as $d)
                <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        <select class="fsel" name="status">
            <option value="">All status</option>
            @foreach(['present','late','absent','on_leave'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="fbtn">Apply</button>
        <a href="{{ route('attendance.index') }}" class="fbtn ghost">Reset</a>
        <span class="f-results">{{ $records->total() }} records</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Hours</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="av" style="background:#DBEAFE;color:#1E40AF;width:28px;height:28px;font-size:10px">
                                {{ $r->employee?->initials }}
                            </div>
                            <span class="td-bold">{{ $r->employee?->full_name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $r->employee?->department }}</td>
                    <td class="td-muted">{{ $r->date->format('M j, Y') }}</td>
                    <td class="td-muted">{{ $r->time_in?->format('h:i A') ?? '—' }}</td>
                    <td class="td-muted">{{ $r->time_out?->format('h:i A') ?? '—' }}</td>
                    <td class="td-bold">{{ $r->hours_worked ? $r->hours_worked . 'h' : '—' }}</td>
                    <td>
                        @php
                        $sc = match($r->status) {
                            'present'  => 'sp-ok',
                            'late'     => 'sp-late',
                            'absent'   => 'sp-no',
                            'on_leave' => 'sp-lv',
                            default    => 'sp-pend',
                        };
                        @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst(str_replace('_',' ',$r->status)) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            No attendance records found
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
