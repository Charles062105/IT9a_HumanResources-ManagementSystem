<x-app-layout title="Edit Task" crumb="HR · Tasks · Edit">

<div class="page-header">
    <div>
        <div class="page-header-title">Edit Task</div>
        <div class="page-header-sub">{{ $assignedTimesheet->title }}</div>
    </div>
    <a href="{{ route('assigned-timesheets.show', $assignedTimesheet) }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <div class="form-title">Update Task Details</div>
    <form method="POST" action="{{ route('assigned-timesheets.update', $assignedTimesheet) }}">
        @csrf
        @method('PATCH')

        <div class="form-row full">
            <div class="form-group">
                <label>Employee *</label>
                <select name="employee_id" required>
                    <option value="">Select an employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id', $assignedTimesheet->employee_id) == $emp->id ? 'selected' : '' }}>
                            {{ $emp->full_name }} ({{ $emp->department }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row full">
            <div class="form-group">
                <label>Task Title *</label>
                <input type="text" name="title" value="{{ old('title', $assignedTimesheet->title) }}" maxlength="255" placeholder="e.g. Complete inventory audit" required>
                @error('title')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row full">
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Detailed instructions or notes for this task...">{{ old('description', $assignedTimesheet->description) }}</textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Expected Hours</label>
                <input type="number" name="expected_hours" value="{{ old('expected_hours', $assignedTimesheet->expected_hours) }}" min="0" max="200" step="0.5" placeholder="0">
            </div>
            <div class="form-group">
                <label>Due Date *</label>
                <input type="date" name="due_date" value="{{ old('due_date', $assignedTimesheet->due_date?->format('Y-m-d')) }}" required>
                @error('due_date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row full">
            <div class="form-group">
                <label>Admin Notes</label>
                <textarea name="admin_notes" rows="2" placeholder="Internal notes about this task...">{{ old('admin_notes', $assignedTimesheet->admin_notes) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="{{ route('assigned-timesheets.show', $assignedTimesheet) }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>