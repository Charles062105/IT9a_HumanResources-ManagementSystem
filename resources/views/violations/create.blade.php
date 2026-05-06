<x-app-layout title="Log Violation" crumb="HR · Violations · New">

<div class="page-header">
    <div>
        <div class="page-header-title">Log Violation</div>
        <div class="page-header-sub">Record a disciplinary action</div>
    </div>
    <a href="{{ route('violations.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('violations.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Employee *</label>
                <select name="employee_id" required>
                    <option value="">Select employee...</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->full_name }} — {{ $emp->department }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Violation Level *</label>
                <select name="level" required>
                    @foreach(['Verbal Warning','Written Warning','Final Warning','Suspension','Termination'] as $l)
                        <option value="{{ $l }}" {{ old('level') == $l ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
                @error('level')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Offense Description *</label>
                <input type="text" name="offense" value="{{ old('offense') }}" placeholder="e.g. Habitual tardiness" required>
                @error('offense')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Date of Offense *</label>
                <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                @error('date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row full">
            <div class="form-group">
                <label>Additional Details / Notes</label>
                <textarea name="description" rows="4" placeholder="Describe the circumstances of the violation...">{{ old('description') }}</textarea>
            </div>
        </div>

        <div style="background:var(--warn-lt);border-radius:8px;padding:12px 14px;margin-bottom:16px;font-size:12px;color:var(--warn)">
            ⚑ This violation will be automatically assigned the next offense count based on the employee's history. Ensure the level matches DOLE progressive discipline guidelines.
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Log Violation</button>
            <a href="{{ route('violations.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>
