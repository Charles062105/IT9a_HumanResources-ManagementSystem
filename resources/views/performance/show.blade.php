<x-app-layout title="Review Details" crumb="HR · Performance · Details">

<div class="page-header">
    <div>
        <div class="page-header-title">Performance Review</div>
        <div class="page-header-sub">{{ $performance->period }}</div>
    </div>
    <div style="display:flex;gap:8px">
        @if(auth()->user()->isAdmin())
        <a href="{{ route('performance.edit', $performance) }}" class="btn-primary" style="text-decoration:none">
            Edit Review
        </a>
        @endif
        <a href="{{ route('performance.index') }}" class="btn-secondary">← Back</a>
    </div>
</div>

<div class="form-card" style="max-width:640px">
    <div class="form-title">Review Summary</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
        <div class="form-group">
            <label>Employee</label>
            <div style="display:flex;align-items:center;gap:8px;padding-top:4px">
                <div class="av av-md" style="background:#DBEAFE;color:#1E40AF;font-size:11px">{{ $performance->employee?->initials }}</div>
                <div>
                    <div style="font-size:13px;font-weight:500;color:var(--text)">{{ $performance->employee?->full_name ?? '—' }}</div>
                    <div style="font-size:11px;color:var(--text3)">{{ $performance->employee?->department }}</div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Review Period</label>
            <div style="font-size:13px;font-weight:500;color:var(--text);padding-top:8px">{{ $performance->period }}</div>
        </div>
        <div class="form-group">
            <label>Score</label>
            <div style="display:flex;align-items:center;gap:10px;padding-top:8px">
                <div class="p-bar" style="width:80px;height:6px"><div class="p-fill" style="width:{{ $performance->score_pct }}%"></div></div>
                <span style="font-size:20px;font-weight:700;color:var(--text);font-family:'Syne',sans-serif">{{ $performance->score }}</span>
                <span style="font-size:12px;color:var(--text3)">/ 10</span>
            </div>
        </div>
        <div class="form-group">
            <label>Rating</label>
            @php
            $sc = match($performance->rating) {
                'Outstanding'       => 'sp-ok',
                'Satisfactory'      => 'sp-rev',
                'Needs Improvement' => 'sp-late',
                default             => 'sp-no',
            };
            @endphp
            <div style="padding-top:6px"><span class="sp {{ $sc }}"><span class="d"></span>{{ $performance->rating }}</span></div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="form-group" style="margin-bottom:0">
        <label>Feedback / Comments</label>
        <div style="font-size:13px;color:var(--text);line-height:1.7;padding:8px 0;min-height:80px;white-space:pre-wrap">{{ $performance->feedback ?? 'No feedback provided.' }}</div>
    </div>

    <div class="divider"></div>

    <div style="display:flex;gap:24px;font-size:11px;color:var(--text3)">
        <div>
            <span style="font-weight:500">Reviewed by:</span> {{ $performance->reviewer?->name ?? '—' }}
        </div>
        <div>
            <span style="font-weight:500">Reviewed on:</span> {{ $performance->created_at->format('M j, Y') }}
        </div>
        @if($performance->updated_at->notEqualTo($performance->created_at))
        <div>
            <span style="font-weight:500">Last updated:</span> {{ $performance->updated_at->format('M j, Y') }}
        </div>
        @endif
    </div>
</div>

</x-app-layout>