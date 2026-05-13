<x-app-layout title="Employees" crumb="HR · Employee management">

<div class="page-header">
    <div>
        <div class="page-header-title">Employees</div>
        <div class="page-header-sub">Manage all employee records</div>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('employees.create') }}" class="btn-primary">
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
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date Hired</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr>
                    <td class="td-mono">{{ $emp->employee_id }}</td>
                    <td>
                        <div class="emp-avatar">
                            <div class="av">{{ $emp->initials }}</div>
                            <span class="td-bold">{{ $emp->full_name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $emp->department }}</td>
                    <td class="td-muted">{{ $emp->position }}</td>
                    <td>
                        @if($emp->user)
                            <span class="role-badge" data-role="{{ $emp->user->role }}">
                                {{ ucfirst(str_replace('_', ' ', $emp->user->role)) }}
                            </span>
                        @else
                            <span class="role-badge" data-role="unassigned">No Account</span>
                        @endif
                    </td>
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
                        <div class="action-group">
                            <a href="{{ route('employees.show', $emp) }}" class="action-link">View</a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('employees.edit', $emp) }}" class="action-link">Edit</a>
                            @if(auth()->user()->isSuperAdmin() && $emp->user)
                                @if(! $emp->user->isAdmin())
                                    <a href="{{ route('users.make-admin', $emp->user) }}" class="action-link action-primary">Make Admin</a>
                                @else
                                    <a href="{{ route('users.revoke-admin-form', $emp->user) }}" class="action-link action-danger">Revoke Admin</a>
                                @endif
                            @endif
                            @if($emp->status !== 'inactive')
                            <form method="POST" action="{{ route('employees.deactivate', $emp) }}" onsubmit="return confirm('Deactivate this employee?')" class="action-form">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-link action-danger">Deactivate</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('employees.activate', $emp) }}" onsubmit="return confirm('Reactivate this employee?')" class="action-form">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-link action-success">Activate</button>
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
                    <a href="{{ route('employees.create') }}" class="btn-primary" style="display:inline-block;margin-top:12px">Create Employee</a>
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
