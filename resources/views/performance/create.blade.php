<x-app-layout title="Add Review" crumb="HR · Performance · New">

<div class="page-header">
    <div>
        <div class="page-header-title">Add Performance Review</div>
        <div class="page-header-sub">Submit a new employee evaluation</div>
    </div>
    <a href="{{ route('performance.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('performance.store') }}">
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
                <label>Review Period *</label>
                <input type="text" name="period" value="{{ old('period', 'Q3 2025') }}" placeholder="e.g. Q3 2025, Annual 2025" required>
                @error('period')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Score (0.0 – 10.0) *</label>
                <input type="number" name="score" value="{{ old('score') }}" min="0" max="10" step="0.1" placeholder="e.g. 8.5" required>
                @error('score')<div class="error-msg">{{ $message }}</div>@enderror
                <div style="font-size:10px;color:var(--text3);margin-top:4px;line-height:1.6">
                    9.0–10.0 → Outstanding &nbsp;·&nbsp; 7.0–8.9 → Satisfactory &nbsp;·&nbsp; 5.0–6.9 → Needs Improvement &nbsp;·&nbsp; Below 5.0 → Poor
                </div>
            </div>
        </div>

        <div class="form-row full">
            <div class="form-group">
                <label>Feedback / Comments</label>
                <textarea name="feedback" rows="5" placeholder="Provide detailed performance feedback, achievements, and areas for improvement...">{{ old('feedback') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Save Review</button>
            <a href="{{ route('performance.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>
