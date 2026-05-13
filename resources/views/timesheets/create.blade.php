<x-app-layout title="Submit Timesheet" crumb="HR · Timesheets · New">

<div class="page-header">
    <div>
        <div class="page-header-title">Submit Timesheet</div>
        <div class="page-header-sub">Log your weekly work hours</div>
    </div>
    <a href="{{ route('timesheets.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <div class="form-title">New Timesheet Submission</div>
    <form method="POST" action="{{ route('timesheets.store') }}">
        @csrf

        @if($isAdmin)
        <div class="form-row full">
            <div class="form-group">
                <label>Employee *</label>
                <select name="employee_id" required>
                    <option value="">Select an employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->full_name }} ({{ $emp->department }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        @endif

        <div class="form-row">
            <div class="form-group">
                <label>Week Start *</label>
                <input type="date" name="week_start" value="{{ old('week_start', now()->startOfWeek()->format('Y-m-d')) }}" required>
                @error('week_start')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Week End *</label>
                <input type="date" name="week_end" value="{{ old('week_end', now()->endOfWeek()->format('Y-m-d')) }}" required>
                @error('week_end')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Total Hours Worked *</label>
                <input type="number" name="total_hours" value="{{ old('total_hours') }}" min="0" max="120" step="0.5" placeholder="e.g. 40" required>
                @error('total_hours')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Overtime Hours</label>
                <input type="number" name="ot_hours" value="{{ old('ot_hours', 0) }}" min="0" step="0.5" placeholder="0">
            </div>
        </div>

        @if($pendingTasks->count())
        <div class="form-row full">
            <div class="form-group">
                <label>Link to Assigned Task (optional)</label>
                <select name="assigned_timesheet_id">
                    <option value="">— General Work (no task linked) —</option>
                    @foreach($pendingTasks as $task)
                        <option value="{{ $task->id }}" {{ old('assigned_timesheet_id') == $task->id ? 'selected' : '' }}>
                            {{ $task->title }} (due {{ $task->due_date->format('M j') }})
                        </option>
                    @endforeach
                </select>
                @error('assigned_timesheet_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        @endif

        <div class="form-row full">
            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" rows="3" placeholder="Any notes about this week's work...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Submit Timesheet</button>
            <a href="{{ route('timesheets.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>
