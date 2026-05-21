<x-app-layout title="Leave Details" crumb="HR - Leaves - Details">

<div class="page-header">
    <div>
        <div class="page-header-title">Leave Details</div>
        <div class="page-header-sub">
            {{ $leave->employee?->full_name ?? 'Unknown' }}
            -
            {{ $leave->start_date?->format('M j, Y') }}
            to
            {{ $leave->end_date?->format('M j, Y') }}
        </div>
    </div>
    <a href="{{ route('leaves.index') }}" class="btn-secondary">Back to List</a>
</div>

<div style="display:flex;flex-direction:column;gap:20px">

    {{-- Employee Information Card --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Employee Information</span>
        </div>
        <div class="card-body" style="padding:14px 18px">
            <div style="display:flex;align-items:center;gap:12px">
                <div class="av av-md" style="background:#DBEAFE;color:#1E40AF;font-size:12px;font-weight:600;width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:50%;flex-shrink:0">
                    {{ $leave->employee?->initials ?? '?' }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:14px;font-weight:600;color:var(--text)">
                        {{ $leave->employee?->full_name ?? 'Unknown' }}
                    </div>
                    <div style="font-size:12px;color:var(--text3);margin-top:2px">
                        {{ $leave->employee?->department ?? 'No department' }}
                        -
                        {{ $leave->employee?->position ?? 'No position' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Leave Details Card --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Leave Details</span>
        </div>
        <div class="card-body" style="padding:0">
            <div style="display:flex;flex-direction:column">

                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 18px;border-bottom:1px solid var(--border)">
                    <span style="font-size:12px;color:var(--text3)">Status</span>
                    @php
                    $sc = match($leave->status) {
                        'approved' => 'sp-ok',
                        'denied' => 'sp-no',
                        default => 'sp-pend'
                    };
                    @endphp
                    <span class="sp {{ $sc }}">
                        <span class="d"></span>
                        {{ ucfirst($leave->status) }}
                    </span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 18px;border-bottom:1px solid var(--border)">
                    <span style="font-size:12px;color:var(--text3)">Type</span>
                    <span style="font-size:13px;font-weight:500;color:var(--text)">
                        {{ ucfirst(str_replace('_', ' ', $leave->type)) }}
                    </span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 18px;border-bottom:1px solid var(--border)">
                    <span style="font-size:12px;color:var(--text3)">Duration</span>
                    <span style="font-size:13px;font-weight:500;color:var(--text)">
                        {{ $leave->days }} day{{ $leave->days != 1 ? 's' : '' }}
                    </span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 18px;border-bottom:1px solid var(--border)">
                    <span style="font-size:12px;color:var(--text3)">Start Date</span>
                    <span style="font-size:13px;font-weight:500;color:var(--text)">
                        {{ $leave->start_date?->format('M j, Y') ?? 'Not set' }}
                    </span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 18px;border-bottom:1px solid var(--border)">
                    <span style="font-size:12px;color:var(--text3)">End Date</span>
                    <span style="font-size:13px;font-weight:500;color:var(--text)">
                        {{ $leave->end_date?->format('M j, Y') ?? 'Not set' }}
                    </span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 18px">
                    <span style="font-size:12px;color:var(--text3)">Filed On</span>
                    <span style="font-size:13px;font-weight:500;color:var(--text)">
                        {{ $leave->created_at?->format('M j, Y') ?? 'Not available' }}
                    </span>
                </div>

            </div>
        </div>
    </div>

    {{-- Approval Information Card --}}
    @if($leave->status !== 'pending')
    <div class="card">
        <div class="card-header">
            <span class="card-title">Approval Information</span>
        </div>
        <div class="card-body" style="padding:0">
            <div style="display:flex;flex-direction:column">

                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 18px;border-bottom:1px solid var(--border)">
                    <span style="font-size:12px;color:var(--text3)">Reviewed By</span>
                    <span style="font-size:13px;font-weight:500;color:var(--text)">
                        {{ $leave->approver?->name ?? 'Unknown' }}
                    </span>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 18px">
                    <span style="font-size:12px;color:var(--text3)">Review Date</span>
                    <span style="font-size:13px;font-weight:500;color:var(--text)">
                        {{ $leave->approved_at?->format('M j, Y') ?? 'Not available' }}
                    </span>
                </div>

            </div>
        </div>
    </div>
    @endif

    {{-- Reason Card --}}
    @if($leave->reason)
    <div class="card">
        <div class="card-header">
            <span class="card-title">Reason</span>
        </div>
        <div class="card-body">
            <p style="font-size:13px;color:var(--text);line-height:1.6;background:var(--bg-secondary);padding:12px;border-radius:6px;margin:0">
                {{ $leave->reason }}
            </p>
        </div>
    </div>
    @endif

</div>

</x-app-layout>