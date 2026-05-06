<x-app-layout title="Add Employee" crumb="HR · Employees · New">

<div class="page-header">
    <div>
        <div class="page-header-title">Add Employee</div>
        <div class="page-header-sub">Create a new employee record</div>
    </div>
    <a href="{{ route('employees.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('employees.store') }}">
        @csrf

        <div class="form-title">Personal Information</div>
        <div class="form-row">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required class="{{ $errors->has('first_name') ? 'input-error' : '' }}">
                @error('first_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required class="{{ $errors->has('last_name') ? 'input-error' : '' }}">
                @error('last_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="{{ $errors->has('email') ? 'input-error' : '' }}">
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX">
            </div>
        </div>
        <div class="form-row full">
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="{{ old('address') }}" placeholder="Full address">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-title">Employment Details</div>
        <div class="form-row">
            <div class="form-group">
                <label>Department *</label>
                <input type="text" name="department" value="{{ old('department') }}" placeholder="e.g. Engineering" required class="{{ $errors->has('department') ? 'input-error' : '' }}">
                @error('department')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Position *</label>
                <input type="text" name="position" value="{{ old('position') }}" placeholder="e.g. Software Engineer" required class="{{ $errors->has('position') ? 'input-error' : '' }}">
                @error('position')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Date Hired *</label>
                <input type="date" name="date_hired" value="{{ old('date_hired') }}" required>
                @error('date_hired')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Employment Status *</label>
                <select name="status" required>
                    @foreach(['active','probationary','contractual'] as $s)
                        <option value="{{ $s }}" {{ old('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Contract Expiry Date</label>
                <input type="date" name="contract_expiry" value="{{ old('contract_expiry') }}">
                <div style="font-size:10px;color:var(--text3);margin-top:3px">Required for contractual employees</div>
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-title">Government IDs (DOLE Compliance)</div>
        <div class="form-row">
            <div class="form-group">
                <label>SSS Number</label>
                <input type="text" name="sss_number" value="{{ old('sss_number') }}" placeholder="00-0000000-0">
            </div>
            <div class="form-group">
                <label>Pag-IBIG (HDMF) Number</label>
                <input type="text" name="pagibig_number" value="{{ old('pagibig_number') }}" placeholder="0000-0000-0000">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>PhilHealth Number</label>
                <input type="text" name="philhealth_number" value="{{ old('philhealth_number') }}" placeholder="00-000000000-0">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Save Employee</button>
            <a href="{{ route('employees.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>
