<x-app-layout title="Notifications" crumb="Admin · Alerts & updates">

<div class="page-header">
    <div>
        <div class="page-header-title">Notifications</div>
        <div class="page-header-sub">System alerts, approvals, and announcements</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <form method="POST" action="{{ route('notifications.read-all') }}" style="display:inline">
            @csrf @method('PATCH')
            <button class="btn-secondary" type="submit">Mark all read</button>
        </form>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('notifications.create') }}" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px">
            + Send Notification
        </a>
        @endif
    </div>
</div>

<div class="section-card">
    <form method="GET" action="{{ route('notifications.index') }}" class="filter-bar">
        <span class="fb-label">Filter</span>
        <div class="fb-sep"></div>
        <select class="fsel" name="type">
            <option value="">All types</option>
            @foreach(['success','warning','danger','info'] as $t)
                <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        <select class="fsel" name="read">
            <option value="">All</option>
            <option value="unread" {{ request('read') == 'unread' ? 'selected' : '' }}>Unread only</option>
            <option value="read"   {{ request('read') == 'read'   ? 'selected' : '' }}>Read only</option>
        </select>
        <button type="submit" class="fbtn">Apply</button>
        @if(request()->anyFilled(['type','read']))
            <a href="{{ route('notifications.index') }}" class="fbtn ghost">Reset</a>
        @endif
        <span class="f-results">{{ $notifications->total() }} notifications</span>
    </form>

    @forelse($notifications as $n)
    <div class="notif-item" style="{{ !$n->is_read ? 'background:rgba(37,99,235,0.025)' : '' }}">
        @if(!$n->is_read)
            <div class="unread-dot"></div>
        @else
            <div style="width:7px;flex-shrink:0"></div>
        @endif

        <div class="notif-icon" style="background:{{ $n->icon_bg ?? 'var(--info-lt)' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="{{ $n->icon_color ?? 'var(--info)' }}" stroke-width="2" width="14" height="14">
                @if($n->type === 'success')
                    <polyline points="20 6 9 17 4 12"/>
                @elseif($n->type === 'warning')
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                @elseif($n->type === 'danger')
                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                @else
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                @endif
            </svg>
        </div>

        <div style="flex:1;min-width:0">
            <div class="notif-title">{{ $n->title }}</div>
            <div class="notif-desc">{{ $n->message }}</div>
        </div>

        <div class="notif-item-actions">
            <span class="notif-time">{{ $n->created_at->diffForHumans() }}</span>
            <div style="display:flex;gap:12px">
                @if(!$n->is_read)
                <form method="POST" action="{{ route('notifications.read', $n) }}" style="display:inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="notif-action-btn notif-action-btn-read">Mark read</button>
                </form>
                @endif
                @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('notifications.destroy', $n) }}" style="display:inline" onsubmit="return confirm('Delete this notification?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="notif-action-btn notif-action-btn-delete">Delete</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        No notifications
    </div>
    @endforelse

    @if($notifications->hasPages())
        <div class="pagination-wrap">{{ $notifications->links() }}</div>
    @endif
</div>

</x-app-layout>
