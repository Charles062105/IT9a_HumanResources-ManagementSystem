<x-app-layout title="Employees" crumb="HR · Employee management">

<div class="page-header">
    <div>
        <div class="page-header-title">Employees</div>
        <div class="page-header-sub">Manage all employee records</div>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('employees.create') }}" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:7px;font-size:12px">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="white"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Add Employee
    </a>
    @endif
</div>

<div class="section-card">
    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('employees.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <input class="finput" type="text" name="search" placeholder="Search name or ID..." value="{{ request('search') }}">
        <select class="fsel" name="department">
            <option value="">All departments</option>
            @foreach($departments as $dept)
            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
            @endforeach
        </select>
        <select class="fsel" name="position">
            <option value="">All positions</option>
            @foreach($positions as $pos)
            <option value="{{ $pos }}" {{ request('position') == $pos ? 'selected' : '' }}>{{ $pos }}</option>
            @endforeach
        </select>
        <select class="fsel" name="status">
            <option value="">All status</option>
            @foreach(['active','probationary','contractual','inactive'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="fbtn">Apply</button>
        <a href="{{ route('employees.index') }}" class="fbtn ghost">Reset</a>
        <span class="f-results">{{ $employees->total() }} record{{ $employees->total() !== 1 ? 's' : '' }}</span>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Date Hired</th>
                    <th>Contract Expiry</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr>
                    <td class="td-mono">{{ $emp->employee_id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="av" style="background:#DBEAFE;color:#1E40AF;width:28px;height:28px;font-size:10px">{{ $emp->initials }}</div>
                            <span class="td-bold">{{ $emp->full_name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $emp->department }}</td>
                    <td class="td-muted">{{ $emp->position }}</td>
                    <td>
                        @php
                        $spClass = match($emp->status) {
                            'active'       => 'sp-ok',
                            'probationary' => 'sp-prob',
                            'contractual'  => 'sp-cont',
                            default        => 'sp-no',
                        };
                        @endphp
                        <span class="sp {{ $spClass }}"><span class="d"></span>{{ ucfirst($emp->status) }}</span>
                    </td>
                    <td class="td-muted">{{ $emp->date_hired?->format('M j, Y') }}</td>
                    <td>
                        @if($emp->contract_expiry)
                            @php $expiring = $emp->contract_expiry->diffInDays(now()) <= 30 && $emp->contract_expiry->isFuture(); @endphp
                            <span style="{{ $expiring ? 'color:var(--warn);font-weight:500' : 'color:var(--text3)' }}">
                                {{ $emp->contract_expiry->format('M j, Y') }}
                                @if($expiring) ⚑ @endif
                            </span>
                        @else
                            <span class="td-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('employees.show', $emp) }}" class="page-link-text">View</a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('employees.edit', $emp) }}" class="page-link-text">Edit</a>
                            @if($emp->status !== 'inactive')
                            <form method="POST" action="{{ route('employees.deactivate', $emp) }}" onsubmit="return confirm('Deactivate this employee?')" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" style="background:none;border:none;font-size:11px;font-weight:500;color:var(--danger);cursor:pointer;font-family:inherit;padding:0">Deactivate</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('employees.activate', $emp) }}" onsubmit="return confirm('Reactivate this employee?')" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" style="background:none;border:none;font-size:11px;font-weight:500;color:#16A34A;cursor:pointer;font-family:inherit;padding:0">Activate</button>
                            </form>
                            @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <div style="margin-top:8px">No employees found</div>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('employees.create') }}" style="display:inline-block;margin-top:12px;padding:8px 16px;background:var(--primary);color:white;border-radius:6px;font-size:12px;text-decoration:none;font-weight:500">Create Employee</a>
                    @endif
                </div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employees->hasPages())
    <div class="pagination-wrap">{{ $employees->links() }}</div>
    @endif
</div>

</x-app-layout>
