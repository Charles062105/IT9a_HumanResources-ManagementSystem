<x-app-layout title="Assign Task" crumb="HR · Tasks · New">

<div class="page-header">
    <div>
        <div class="page-header-title">Assign Task</div>
        <div class="page-header-sub">Assign a work task to an employee</div>
    </div>
    <a href="{{ route('assigned-timesheets.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <div class="form-title">Assign New Task</div>
    <form method="POST" action="{{ route('assigned-timesheets.store') }}">
        @csrf

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

        <div class="form-row full">
            <div class="form-group">
                <label>Task Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" maxlength="255" placeholder="e.g. Complete inventory audit" required>
                @error('title')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row full">
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" placeholder="Detailed instructions or notes for this task...">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Expected Hours</label>
                <input type="number" name="expected_hours" value="{{ old('expected_hours', 0) }}" min="0" max="200" step="0.5" placeholder="0">
            </div>
            <div class="form-group">
                <label>Due Date *</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" required>
                @error('due_date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Assign Task</button>
            <a href="{{ route('assigned-timesheets.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>
