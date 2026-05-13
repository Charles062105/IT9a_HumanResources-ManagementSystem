<x-app-layout title="Send Notification" crumb="Admin · Notifications · New">

<div class="page-header">
    <div>
        <div class="page-header-title">Send Notification</div>
        <div class="page-header-sub">Broadcast a system-wide alert to all employees.</div>
    </div>
    <a href="{{ route('notifications.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <div class="form-title">Send Notification</div>
    <form method="POST" action="{{ route('notifications.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Notification Type *</label>
                <select name="type" required>
                    <option value="info"    {{ old('type') == 'info'    ? 'selected' : '' }}>Info (Blue)</option>
                    <option value="success" {{ old('type') == 'success' ? 'selected' : '' }}>Success (Green)</option>
                    <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>Warning (Amber)</option>
                    <option value="error"   {{ in_array(old('type'), ['error','danger']) ? 'selected' : '' }}>Danger (Red)</option>
                </select>
                @error('type')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row full">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Short, clear notification title" required>
                @error('title')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row full">
            <div class="form-group">
                <label>Message *</label>
                <textarea name="message" rows="4" placeholder="Detailed notification message...">{{ old('message') }}</textarea>
                @error('message')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Send Notification</button>
            <a href="{{ route('notifications.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-app-layout>
