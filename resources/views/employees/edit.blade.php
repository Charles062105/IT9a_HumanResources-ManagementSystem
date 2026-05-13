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

        <div class="form-title">Personal information</div>
        <div class="form-row">
            <div class="form-group">
                <label>First name <span style="color:var(--danger,#ef4444);font-weight:700" aria-label="required">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required aria-required="true" class="{{ $errors->has('first_name') ? 'input-error' : '' }}" aria-label="First name">
                @error('first_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Last name <span style="color:var(--danger,#ef4444);font-weight:700" aria-label="required">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required aria-required="true" class="{{ $errors->has('last_name') ? 'input-error' : '' }}" aria-label="Last name">
                @error('last_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email address <span style="color:var(--danger,#ef4444);font-weight:700" aria-label="required">*</span></label>
                <input type="email" name="email" value="{{ old('email', $employee->email) }}" required aria-required="true" class="{{ $errors->has('email') ? 'input-error' : '' }}" aria-label="Email address">
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Phone number</label>
                <input type="tel" name="phone" value="{{ old('phone', $employee->phone) }}" class="{{ $errors->has('phone') ? 'input-error' : '' }}" placeholder="+63 9XX XXX XXXX" aria-label="Phone number">
                @error('phone')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row full">
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="{{ old('address', $employee->address) }}" class="{{ $errors->has('address') ? 'input-error' : '' }}">
                @error('address')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Date of birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}" class="{{ $errors->has('date_of_birth') ? 'input-error' : '' }}">
                @error('date_of_birth')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-title">Employment details</div>
        <div class="form-row">
            <div class="form-group">
                <label>Department <span style="color:var(--danger,#ef4444);font-weight:700" aria-label="required">*</span></label>
                <input type="text" name="department" value="{{ old('department', $employee->department) }}" list="departments" required aria-required="true" class="{{ $errors->has('department') ? 'input-error' : '' }}" aria-label="Department">
                <datalist id="departments">
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}">
                    @endforeach
                </datalist>
                @error('department')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Position <span style="color:var(--danger,#ef4444);font-weight:700" aria-label="required">*</span></label>
                <input type="text" name="position" value="{{ old('position', $employee->position) }}" list="positions" required aria-required="true" class="{{ $errors->has('position') ? 'input-error' : '' }}" aria-label="Position">
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
                <label>Date hired <span style="color:var(--danger,#ef4444);font-weight:700" aria-label="required">*</span></label>
                <input type="date" name="date_hired" value="{{ old('date_hired', $employee->date_hired->format('Y-m-d')) }}" required aria-required="true" class="{{ $errors->has('date_hired') ? 'input-error' : '' }}" aria-label="Date hired">
                @error('date_hired')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Employment status <span style="color:var(--danger,#ef4444);font-weight:700" aria-label="required">*</span></label>
                <select name="status" required aria-required="true" id="empStatus" onchange="toggleContractExpiry(this.value)" class="{{ $errors->has('status') ? 'input-error' : '' }}" aria-label="Employment status">
                    @foreach(['active','probationary','contractual','inactive'] as $s)
                        <option value="{{ $s }}" {{ old('status', $employee->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                @error('status')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Shift assignment</label>
                <select name="shift_id" id="shiftSelect" class="{{ $errors->has('shift_id') ? 'input-error' : '' }}" aria-label="Shift assignment">
                    <option value="">-- No shift assigned --</option>
                    @foreach($shifts ?? [] as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }}>
                            {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                        </option>
                    @endforeach
                </select>
                <div style="font-size:10px;color:var(--text3);margin-top:3px">Assign employee to a work shift</div>
                @error('shift_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label id="contractExpiryLabel">Contract expiry date</label>
                <input type="date" name="contract_expiry" value="{{ old('contract_expiry', $employee->contract_expiry?->format('Y-m-d')) }}" id="contractExpiryInput" class="{{ $errors->has('contract_expiry') ? 'input-error' : '' }}">
                <div style="font-size:10px;color:var(--text3);margin-top:3px" id="contractExpiryHelp">Required for contractual employees</div>
                @error('contract_expiry')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-title">Government IDs</div>
        <div class="form-row">
            <div class="form-group">
                <label>SSS number</label>
                <input type="text" name="sss_number" value="{{ old('sss_number', $employee->sss_number) }}" class="{{ $errors->has('sss_number') ? 'input-error' : '' }}" placeholder="00-0000000-0" title="Format: 00-0000000-0">
                @error('sss_number')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Pag-IBIG number</label>
                <input type="text" name="pagibig_number" value="{{ old('pagibig_number', $employee->pagibig_number) }}" class="{{ $errors->has('pagibig_number') ? 'input-error' : '' }}" placeholder="0000-0000-0000" title="Format: 0000-0000-0000">
                @error('pagibig_number')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>PhilHealth number</label>
                <input type="text" name="philhealth_number" value="{{ old('philhealth_number', $employee->philhealth_number) }}" class="{{ $errors->has('philhealth_number') ? 'input-error' : '' }}" placeholder="00-000000000-0" title="Format: 00-000000000-0">
                @error('philhealth_number')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary" id="submitBtn" onclick="setLoading(event)">
                <span id="submitText">Save Changes</span>
                <span id="submitLoader" style="display:none; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 0.8s linear infinite;"></span>
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
        label.innerHTML = 'Contract expiry date <span style="color:var(--danger,#ef4444);font-weight:700">*</span>';
        help.innerHTML = 'Required for contractual employees — alerts will trigger 30 days before.';
    } else {
        input.required = false;
        input.value = '';
        label.textContent = 'Contract expiry date';
        help.textContent = 'Required for contractual employees';
    }
}

let formDirty = false;

document.addEventListener('DOMContentLoaded', function() {
    toggleContractExpiry(document.getElementById('empStatus').value);

    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('input', function() {
        formDirty = true;
    });

    form.addEventListener('change', function() {
        formDirty = true;
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
