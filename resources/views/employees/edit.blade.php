<x-app-layout title="Edit Employee" crumb="HR · Employees · Edit">

<div class="page-header">
    <div>
        <div class="page-header-title">Edit Employee</div>
        <div class="page-header-sub">{{ $employee->full_name }} — {{ $employee->employee_id }}</div>
    </div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('employees.show', $employee) }}" class="btn-secondary">View Profile</a>
        <a href="{{ route('employees.index') }}" class="btn-secondary">← Back</a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('employees.update', $employee) }}">
        @csrf @method('PUT')

        <div class="form-title">Personal Information</div>
        <div class="form-row">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                @error('first_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
                @error('last_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $employee->email) }}" required>
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}">
            </div>
        </div>
        <div class="form-row full">
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="{{ old('address', $employee->address) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-title">Employment Details</div>
        <div class="form-row">
            <div class="form-group">
                <label>Department *</label>
                <input type="text" name="department" value="{{ old('department', $employee->department) }}" required>
            </div>
            <div class="form-group">
                <label>Position *</label>
                <input type="text" name="position" value="{{ old('position', $employee->position) }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Date Hired *</label>
                <input type="date" name="date_hired" value="{{ old('date_hired', $employee->date_hired->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label>Employment Status *</label>
                <select name="status" required>
                    @foreach(['active','probationary','contractual','inactive'] as $s)
                        <option value="{{ $s }}" {{ old('status', $employee->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Contract Expiry</label>
                <input type="date" name="contract_expiry" value="{{ old('contract_expiry', $employee->contract_expiry?->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-title">Government IDs</div>
        <div class="form-row">
            <div class="form-group">
                <label>SSS Number</label>
                <input type="text" name="sss_number" value="{{ old('sss_number', $employee->sss_number) }}">
            </div>
            <div class="form-group">
                <label>Pag-IBIG Number</label>
                <input type="text" name="pagibig_number" value="{{ old('pagibig_number', $employee->pagibig_number) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>PhilHealth Number</label>
                <input type="text" name="philhealth_number" value="{{ old('philhealth_number', $employee->philhealth_number) }}">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="{{ route('employees.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>
