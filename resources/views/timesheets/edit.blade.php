<x-app-layout title="Edit Timesheet" crumb="HR · Timesheets · Edit">

<div class="page-header">
    <div>
        <div class="page-header-title">Edit Timesheet</div>
        <div class="page-header-sub">{{ $timesheet->week_label }}</div>
    </div>
    <a href="{{ auth()->user()->isAdmin() ? route('timesheets.index') : route('timesheets.my') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <div class="form-title">Update Submission</div>
    <form method="POST" action="{{ route('timesheets.update', $timesheet) }}">
        @csrf
        @method('PATCH')

        <div class="form-row">
            <div class="form-group">
                <label>Total Hours Worked *</label>
                <input type="number" name="total_hours" value="{{ old('total_hours', $timesheet->total_hours) }}" min="0" max="120" step="0.5" required>
                @error('total_hours')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Overtime Hours</label>
                <input type="number" name="ot_hours" value="{{ old('ot_hours', $timesheet->ot_hours ?? 0) }}" min="0" step="0.5" placeholder="0">
                @error('ot_hours')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row full">
            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" rows="3" placeholder="Add or update notes about this week's work...">{{ old('notes', $timesheet->notes) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="{{ auth()->user()->isAdmin() ? route('timesheets.index') : route('timesheets.my') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>