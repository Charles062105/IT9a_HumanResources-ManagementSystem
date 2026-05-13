<x-app-layout title="Violation Details" crumb="HR · Disciplinary tracker">

<div class="page-header">
    <div>
        <div class="page-header-title">Violation Details</div>
        <div class="page-header-sub">Disciplinary record and resolution tracking</div>
    </div>
    <a href="{{ route('violations.index') }}" class="btn-secondary">← Back</a>
</div>

<div class="form-card">
    <div class="form-title">Record Details</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div class="form-group">
            <label>Employee</label>
            <div style="display:flex;align-items:center;gap:8px;padding-top:4px">
                <div class="av av-sm" style="background:#FEE2E2;color:#991B1B;font-size:9px">{{ $violation->employee?->initials }}</div>
                <div>
                    <div style="font-size:13px;font-weight:500;color:var(--text)">{{ $violation->employee?->full_name ?? '—' }}</div>
                    <div style="font-size:11px;color:var(--text3)">{{ $violation->employee?->department }}</div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Discipline Level</label>
            @php $badge = $violation->level_badge_color; @endphp
            <div style="padding-top:4px">
                <span class="sp" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border-radius:5px;display:inline-block;padding:6px 12px;font-weight:500">{{ $violation->level }}</span>
            </div>
        </div>
        <div class="form-group">
            <label>Status</label>
            @php $sc = $violation->status === 'open' ? 'sp-open' : 'sp-ok'; @endphp
            <div style="padding-top:4px"><span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($violation->status) }}</span></div>
        </div>
        <div class="form-group">
            <label>Offense #</label>
            <div style="padding-top:4px">
                <span class="vb" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};display:inline-block;padding:4px 10px;border-radius:5px;font-weight:600">{{ $violation->offense_count }}</span>
            </div>
        </div>
        <div class="form-group">
            <label>Date</label>
            <div style="font-size:13px;font-weight:500;color:var(--text);padding-top:8px">{{ $violation->date?->format('M j, Y') }}</div>
        </div>
        <div class="form-group">
            <label>Issued By</label>
            <div style="font-size:13px;font-weight:500;color:var(--text);padding-top:8px">{{ $violation->issuer?->name ?? 'Admin' }}</div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="form-group">
        <label>Offense</label>
        <div style="font-size:14px;color:var(--text);font-weight:500;padding:6px 0">{{ $violation->offense }}</div>
    </div>

    @if($violation->description)
    <div class="divider"></div>
    <div class="form-group" style="margin-bottom:0">
        <label>Description</label>
        <div style="font-size:13px;color:var(--text2);line-height:1.6;white-space:pre-wrap;padding:6px 0">{{ $violation->description }}</div>
    </div>
    @endif

    @if(auth()->user()->isAdmin())
    <div class="divider"></div>
    <div style="display:flex;gap:10px">
        @if($violation->status === 'open')
        <button type="button" class="btn-success" style="padding:8px 16px" onclick="openResolveModal()">Mark as Resolved</button>
        @endif
    </div>
    @endif
</div>

{{-- Resolve Modal --}}
<div id="resolveModal" class="modal-overlay">
    <div class="modal-dialog" style="max-width:400px">
        <div class="modal-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            Mark as Resolved
        </div>
        <div class="modal-body">
            <p style="margin:0;font-size:13px;color:var(--text2);line-height:1.6">
                Mark <strong style="color:var(--text)">{{ $violation->employee?->full_name }}</strong>'s violation<br>
                "<em style="color:var(--text)">{{ $violation->offense }}</em>" as resolved?
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-deny" onclick="closeResolveModal()">Cancel</button>
            <form method="POST" action="{{ route('violations.resolve', $violation) }}" style="display:inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn-success" style="padding:8px 16px">Resolve</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openResolveModal() {
    var m = document.getElementById('resolveModal');
    m.style.display = 'flex'; m.classList.add('show');
}
function closeResolveModal() {
    var m = document.getElementById('resolveModal');
    m.style.display = 'none'; m.classList.remove('show');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeResolveModal(); }
});
</script>
@endpush

</x-app-layout>