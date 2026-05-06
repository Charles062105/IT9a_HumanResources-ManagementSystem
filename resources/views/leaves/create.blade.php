<x-app-layout title="File Leave" crumb="HR · Leaves · New">

<div class="page-header">
    <div>
        <div class="page-header-title">File Leave Request</div>
        <div class="page-header-sub">Submit a leave application</div>
    </div>
    <a href="{{ route('leaves.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('leaves.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Employee *</label>
                <select name="employee_id" required>
                    <option value="">Select employee...</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->full_name }} ({{ $emp->department }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Leave Type *</label>
                <select name="type" required>
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
                <input type="date" name="start_date" value="{{ old('start_date') }}" required>
                @error('start_date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>End Date *</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" required>
                @error('end_date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

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

</x-app-layout>
