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
                <label for="first_name">First Name <span class="required">*</span></label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="{{ $errors->has('first_name') ? 'input-error' : '' }}">
                @error('first_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="last_name">Last Name <span class="required">*</span></label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="{{ $errors->has('last_name') ? 'input-error' : '' }}">
                @error('last_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="{{ $errors->has('email') ? 'input-error' : '' }}">
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX">
            </div>
        </div>
        <div class="form-row full">
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Full address">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-title">Employment Details</div>
        <div class="form-row">
            <div class="form-group">
                <label for="department">Department <span class="required">*</span></label>
                <input type="text" id="department" name="department" value="{{ old('department') }}" placeholder="e.g. Engineering" required class="{{ $errors->has('department') ? 'input-error' : '' }}">
                @error('department')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="position">Position <span class="required">*</span></label>
                <input type="text" id="position" name="position" value="{{ old('position') }}" placeholder="e.g. Software Engineer" required class="{{ $errors->has('position') ? 'input-error' : '' }}">
                @error('position')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="date_hired">Date Hired <span class="required">*</span></label>
                <input type="date" id="date_hired" name="date_hired" value="{{ old('date_hired') }}" required class="{{ $errors->has('date_hired') ? 'input-error' : '' }}">
                <div class="form-help">Must be today or earlier</div>
                @error('date_hired')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="status">Employment Status <span class="required">*</span></label>
                <select id="status" name="status" required>
                    @foreach(['active','probationary','contractual'] as $s)
                        <option value="{{ $s }}" {{ old('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="shift_id">Shift Assignment</label>
                <select id="shift_id" name="shift_id" class="{{ $errors->has('shift_id') ? 'input-error' : '' }}">
                    <option value="">-- No shift assigned --</option>
                    @foreach($shifts ?? [] as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                            {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                        </option>
                    @endforeach
                </select>
                <div class="form-help">Assign employee to a work shift</div>
                @error('shift_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="contract_expiry">Contract Expiry Date</label>
                <input type="date" id="contract_expiry" name="contract_expiry" value="{{ old('contract_expiry') }}" class="{{ $errors->has('contract_expiry') ? 'input-error' : '' }}">
                <div class="form-help">Required for contractual employees</div>
                @error('contract_expiry')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-title">Government IDs (DOLE Compliance)</div>
        <div class="form-row">
            <div class="form-group">
                <label for="sss_number">SSS Number</label>
                <input type="text" id="sss_number" name="sss_number" value="{{ old('sss_number') }}" placeholder="00-0000000-0" title="Format: 00-0000000-0">
            </div>
            <div class="form-group">
                <label for="pagibig_number">Pag-IBIG (HDMF) Number</label>
                <input type="text" id="pagibig_number" name="pagibig_number" value="{{ old('pagibig_number') }}" placeholder="0000-0000-0000" title="Format: 0000-0000-0000">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="philhealth_number">PhilHealth Number</label>
                <input type="text" id="philhealth_number" name="philhealth_number" value="{{ old('philhealth_number') }}" placeholder="00-000000000-0" title="Format: 00-000000000-0">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Save Employee</button>
            <a href="{{ route('employees.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>
