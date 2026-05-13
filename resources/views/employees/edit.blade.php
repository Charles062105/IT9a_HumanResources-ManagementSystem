<x-app-layout title="Edit Employee" crumb="HR · Employees · Edit">

<div class="page-header">
    <div>
        <div class="page-header-title">Edit Employee</div>
        <div class="page-header-sub">{{ $employee->full_name }} — {{ $employee->employee_id }}</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('employees.show', $employee) }}" class="btn-secondary">View Profile</a>
        <a href="{{ route('employees.index') }}" class="btn-secondary">← Back</a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('employees.update', $employee) }}">
        @csrf @method('PUT')

        <div class="form-title">Personal information</div>
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First name <span class="required">*</span></label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required aria-required="true" class="{{ $errors->has('first_name') ? 'input-error' : '' }}">
                @error('first_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="last_name">Last name <span class="required">*</span></label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required aria-required="true" class="{{ $errors->has('last_name') ? 'input-error' : '' }}">
                @error('last_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email address <span class="required">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}" required aria-required="true" class="{{ $errors->has('email') ? 'input-error' : '' }}">
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="phone">Phone number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" placeholder="+63 9XX XXX XXXX" class="{{ $errors->has('phone') ? 'input-error' : '' }}">
                @error('phone')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row full">
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address', $employee->address) }}" class="{{ $errors->has('address') ? 'input-error' : '' }}">
                @error('address')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="date_of_birth">Date of birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}" class="{{ $errors->has('date_of_birth') ? 'input-error' : '' }}">
                @error('date_of_birth')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-title">Employment details</div>
        <div class="form-row">
            <div class="form-group">
                <label for="department">Department <span class="required">*</span></label>
                <input type="text" id="department" name="department" value="{{ old('department', $employee->department) }}" list="departments" required aria-required="true" class="{{ $errors->has('department') ? 'input-error' : '' }}">
                <datalist id="departments">
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}">
                    @endforeach
                </datalist>
                @error('department')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="position">Position <span class="required">*</span></label>
                <input type="text" id="position" name="position" value="{{ old('position', $employee->position) }}" list="positions" required aria-required="true" class="{{ $errors->has('position') ? 'input-error' : '' }}">
                <datalist id="positions">
                    @foreach($positions as $pos)
                        <option value="{{ $pos }}">
                    @endforeach
                </datalist>
                @error('position')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="date_hired">Date hired <span class="required">*</span></label>
                <input type="date" id="date_hired" name="date_hired" value="{{ old('date_hired', $employee->date_hired->format('Y-m-d')) }}" required aria-required="true" class="{{ $errors->has('date_hired') ? 'input-error' : '' }}">
                <div class="form-help">Must be today or earlier</div>
                @error('date_hired')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="empStatus">Employment status <span class="required">*</span></label>
                <select id="empStatus" name="status" required aria-required="true" onchange="toggleContractExpiry(this.value)" class="{{ $errors->has('status') ? 'input-error' : '' }}">
                    @foreach(['active','probationary','contractual','inactive'] as $s)
                        <option value="{{ $s }}" {{ old('status', $employee->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                @error('status')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="shiftSelect">Shift assignment</label>
                <select id="shiftSelect" name="shift_id" class="{{ $errors->has('shift_id') ? 'input-error' : '' }}">
                    <option value="">-- No shift assigned --</option>
                    @foreach($shifts ?? [] as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }}>
                            {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                        </option>
                    @endforeach
                </select>
                <div class="form-help">Assign employee to a work shift</div>
                @error('shift_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label id="contractExpiryLabel" for="contractExpiryInput">Contract expiry date</label>
                <input type="date" id="contractExpiryInput" name="contract_expiry" value="{{ old('contract_expiry', $employee->contract_expiry?->format('Y-m-d')) }}" class="{{ $errors->has('contract_expiry') ? 'input-error' : '' }}">
                <div class="form-help" id="contractExpiryHelp">Required for contractual employees</div>
                @error('contract_expiry')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-title">Government IDs</div>
        <div class="form-row">
            <div class="form-group">
                <label for="sss_number">SSS number</label>
                <input type="text" id="sss_number" name="sss_number" value="{{ old('sss_number', $employee->sss_number) }}" placeholder="00-0000000-0" title="Format: 00-0000000-0" class="{{ $errors->has('sss_number') ? 'input-error' : '' }}">
                @error('sss_number')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="pagibig_number">Pag-IBIG number</label>
                <input type="text" id="pagibig_number" name="pagibig_number" value="{{ old('pagibig_number', $employee->pagibig_number) }}" placeholder="0000-0000-0000" title="Format: 0000-0000-0000" class="{{ $errors->has('pagibig_number') ? 'input-error' : '' }}">
                @error('pagibig_number')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="philhealth_number">PhilHealth number</label>
                <input type="text" id="philhealth_number" name="philhealth_number" value="{{ old('philhealth_number', $employee->philhealth_number) }}" placeholder="00-000000000-0" title="Format: 00-000000000-0" class="{{ $errors->has('philhealth_number') ? 'input-error' : '' }}">
                @error('philhealth_number')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        @if(auth()->user()->isSuperAdmin())
        <div class="divider"></div>
        <div class="form-title">System Role</div>
        <div class="form-row full">
            <div class="form-group">
                <label for="role">User Role <span class="required">*</span></label>
                <select id="role" name="role" required class="{{ $errors->has('role') ? 'input-error' : '' }}" onchange="updateRole(this.value, {{ $employee->id }})">
                    <option value="">-- Select Role --</option>
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ ($employee->user?->role ?? 'employee') == $r ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $r)) }}
                        </option>
                    @endforeach
                </select>
                <div class="form-help">{{ ($employee->user?->role ?? 'employee') == 'employee' ? 'Regular employee account' : 'Admin access - can manage system' }}</div>
                @error('role')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        @if($employee->user?->isAdmin())
        <div class="form-row full">
            <div class="revoke-admin-section">
                <div class="revoke-admin-warning">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        <div class="revoke-admin-title">Revoke Admin Access</div>
                        <div class="revoke-admin-text">{{ $employee->full_name }} currently has {{ ucfirst(str_replace('_', ' ', $employee->user->role)) }} role</div>
                    </div>
                </div>
                <a href="{{ route('users.revoke-admin-form', $employee->user) }}" class="btn-danger">Revoke Admin Role</a>
            </div>
        </div>
        @endif
        @endif

        <div class="form-actions">
            <button type="submit" class="btn-primary" id="submitBtn" onclick="setLoading(event)">
                <span id="submitText">Save Changes</span>
                <span id="submitLoader" class="btn-loader"></span>
            </button>
            <a href="{{ route('employees.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function setLoading(event) {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    document.getElementById('submitText').style.display = 'none';
    document.getElementById('submitLoader').style.display = 'inline-block';
}

function toggleContractExpiry(val) {
    const input = document.getElementById('contractExpiryInput');
    const label = document.getElementById('contractExpiryLabel');
    const help = document.getElementById('contractExpiryHelp');

    if (val === 'contractual') {
        input.required = true;
        label.innerHTML = 'Contract expiry date <span class="required">*</span>';
        help.innerHTML = 'Required for contractual employees — alerts will trigger 30 days before.';
    } else {
        input.required = false;
        input.value = '';
        label.textContent = 'Contract expiry date';
        help.textContent = 'Required for contractual employees';
    }
}

function updateRole(value, employeeId) {
    if (!value) return;

    fetch(`/employees/${employeeId}/role`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ role: value })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const help = document.querySelector('.form-group:has(#role) .form-help');
            help.textContent = value === 'employee' ? 'Regular employee account' : 'Admin access - can manage system';
        }
    })
    .catch(() => {
        alert('Failed to update role. Please try again.');
        location.reload();
    });
}

let formDirty = false;

document.addEventListener('DOMContentLoaded', function() {
    toggleContractExpiry(document.getElementById('empStatus').value);

    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('input', function() {
        formDirty = true;
    });

    form.addEventListener('change', function(e) {
        if (e.target.id !== 'role') formDirty = true;
    });

    form.addEventListener('submit', function() {
        formDirty = false;
    });

    window.addEventListener('beforeunload', function(e) {
        if (formDirty) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });
});
</script>

</x-app-layout>
