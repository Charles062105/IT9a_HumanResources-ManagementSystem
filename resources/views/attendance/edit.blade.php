<x-app-layout title="Edit Attendance" crumb="HR · Attendance · Edit">

<div class="page-header">
    <div>
        <div class="page-header-title">Edit Attendance Record</div>
        <div class="page-header-sub">{{ $attendance->employee?->full_name ?? '—' }} — {{ $attendance->date->format('M j, Y') }}</div>
    </div>
    <a href="{{ route('attendance.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <div class="form-title">Record Details</div>
    <form method="POST" action="{{ route('attendance.update', $attendance) }}">
        @csrf
        @method('PATCH')

        <div class="form-row">
            <div class="form-group">
                <label>Employee *</label>
                <select name="employee_id" required>
                    <option value="">Select employee...</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id', $attendance->employee_id) == $emp->id ? 'selected' : '' }}>
                            {{ $emp->full_name }} — {{ $emp->department }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Date *</label>
                <input type="date" name="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" required>
                @error('date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Time In</label>
                <input type="datetime-local" name="time_in" value="{{ old('time_in', $attendance->time_in?->format('Y-m-d\TH:i')) }}">
                @error('time_in')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Time Out</label>
                <input type="datetime-local" name="time_out" value="{{ old('time_out', $attendance->time_out?->format('Y-m-d\TH:i')) }}">
                @error('time_out')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    @foreach(['present','late','absent','half_day','on_leave'] as $s)
                        <option value="{{ $s }}" {{ old('status', $attendance->status) == $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_',' ',$s)) }}
                        </option>
                    @endforeach
                </select>
                @error('status')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Notes</label>
                <input type="text" name="notes" value="{{ old('notes', $attendance->notes) }}" placeholder="Optional notes...">
                @error('notes')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Record</button>
            <a href="{{ route('attendance.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>