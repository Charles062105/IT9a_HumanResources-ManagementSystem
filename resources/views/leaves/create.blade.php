<x-app-layout title="File Leave" crumb="HR · Leaves · New">

<div class="page-header">
    <div>
        <div class="page-header-title">File Leave Request</div>
        <div class="page-header-sub">Submit a leave application</div>
    </div>
    <a href="{{ route('leaves.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <div class="form-title">New Leave Request</div>
    <form method="POST" action="{{ route('leaves.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Employee *</label>
                @if(auth()->user()->isEmployee() && auth()->user()->employee)
                    <input type="text" value="{{ auth()->user()->employee->full_name }}" disabled style="background:var(--bg-secondary)">
                    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                    <div style="font-size:10px;color:var(--text3);margin-top:3px">Your account is pre-selected</div>
                @else
                    <select name="employee_id" required class="{{ $errors->has('employee_id') ? 'input-error' : '' }}">
                        <option value="">Select employee...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }} ({{ $emp->department }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')<div class="error-msg">{{ $message }}</div>@enderror
                @endif
            </div>
            <div class="form-group">
                <label>Leave Type *</label>
                <select name="type" required class="{{ $errors->has('type') ? 'input-error' : '' }}">
                    <option value="">Select leave type...</option>
                    @foreach(['vacation','sick','emergency','maternity','paternity','solo_parent'] as $t)
                        <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ',$t)) }}
                        </option>
                    @endforeach
                </select>
                @error('type')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Start Date *</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="{{ $errors->has('start_date') ? 'input-error' : '' }}">
                @error('start_date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>End Date *</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required class="{{ $errors->has('end_date') ? 'input-error' : '' }}">
                @error('end_date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row full" id="duration-display" style="display:none;background:var(--bg-secondary);padding:12px;border-radius:6px">
            <div style="font-size:12px;font-weight:500;color:var(--text)">Duration: <span id="duration-days">0</span> day<span id="duration-plural">s</span></div>
        </div>

        <div id="date-error" style="display:none;color:var(--danger);font-size:11px;font-weight:500;margin-top:-8px;margin-bottom:8px">End date must be on or after start date</div>

        <div class="form-row full">
            <div class="form-group">
                <label>Reason / Details</label>
                <textarea name="reason" rows="3" placeholder="Briefly describe the reason for your leave...">{{ old('reason') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Submit Request</button>
            <a href="{{ route('leaves.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const durationDisplay = document.getElementById('duration-display');
    const durationDays = document.getElementById('duration-days');
    const durationPlural = document.getElementById('duration-plural');
    const dateError = document.getElementById('date-error');

    function calculateDuration() {
        if (startInput.value && endInput.value) {
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);
            if (start <= end) {
                const days = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
                durationDays.textContent = days;
                durationPlural.textContent = days !== 1 ? 's' : '';
                durationDisplay.style.display = 'block';
                dateError.style.display = 'none';
            } else {
                durationDisplay.style.display = 'none';
                dateError.style.display = 'block';
            }
        }
    }

    startInput.addEventListener('change', calculateDuration);
    endInput.addEventListener('change', calculateDuration);

    // Calculate on page load if values exist
    calculateDuration();
});
</script>

</x-app-layout>
