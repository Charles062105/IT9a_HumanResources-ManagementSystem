<x-app-layout title="Leave Details" crumb="HR · Leaves · Details">

<div class="page-header">
    <div>
        <div class="page-header-title">{{ ucfirst($leave->type) }} Leave</div>
        <div class="page-header-sub">{{ $leave->employee?->full_name }} · {{ $leave->start_date?->format('M j, Y') }} to {{ $leave->end_date?->format('M j, Y') }}</div>
    </div>
    <a href="{{ route('leaves.index') }}" class="btn-secondary">← Back</a>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:16px;align-items:start">

    {{-- Main Details --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Leave Details</span></div>
        <div class="card-body">
            <div style="display:grid;gap:16px">
                
                {{-- Status Badge --}}
                <div>
                    <div style="font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Status</div>
                    @php $sc = match($leave->status) { 'approved' => 'sp-ok', 'denied' => 'sp-no', default => 'sp-pend' }; @endphp
                    <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($leave->status) }}</span>
                </div>

                {{-- Duration --}}
                <div style="border-top:1px solid var(--border);padding-top:12px">
                    <div style="font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Duration</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div>
                            <div style="font-size:10px;color:var(--text3)">Start Date</div>
                            <div style="font-size:13px;font-weight:500;color:var(--text);margin-top:3px">{{ $leave->start_date->format('M j, Y') }}</div>
                        </div>
                        <div>
                            <div style="font-size:10px;color:var(--text3)">End Date</div>
                            <div style="font-size:13px;font-weight:500;color:var(--text);margin-top:3px">{{ $leave->end_date->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div style="margin-top:10px">
                        <div style="font-size:10px;color:var(--text3)">Total Days</div>
                        <div style="font-size:15px;font-weight:600;color:var(--text);margin-top:3px">{{ $leave->days }} day{{ $leave->days != 1 ? 's' : '' }}</div>
                    </div>
                </div>

                {{-- Leave Type --}}
                <div style="border-top:1px solid var(--border);padding-top:12px">
                    <div style="font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Leave Type</div>
                    <div style="font-size:13px;font-weight:500;color:var(--text)">{{ ucfirst(str_replace('_', ' ', $leave->type)) }}</div>
                </div>

                {{-- Reason --}}
                @if($leave->reason)
                <div style="border-top:1px solid var(--border);padding-top:12px">
                    <div style="font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Reason</div>
                    <div style="font-size:12px;color:var(--text);line-height:1.5;background:var(--bg-secondary);padding:10px;border-radius:6px">{{ $leave->reason }}</div>
                </div>
                @endif

                {{-- Filed Date --}}
                <div style="border-top:1px solid var(--border);padding-top:12px">
                    <div style="font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Filed On</div>
                    <div style="font-size:12px;color:var(--text)">{{ $leave->created_at->format('M j, Y \a\t h:i A') }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- Approval Info Sidebar --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Approval</span></div>
        <div class="card-body" style="padding:14px 18px">
            @if($leave->status !== 'pending')
                <div style="display:flex;flex-direction:column;gap:12px">
                    <div>
                        <div style="font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">Status</div>
                        <div style="font-size:12px;font-weight:500;color:var(--text)">{{ ucfirst($leave->status) }}</div>
                    </div>
                    <div style="border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">Approved By</div>
                        <div style="font-size:12px;font-weight:500;color:var(--text)">
                            {{ $leave->approver?->name ?? '—' }}
                        </div>
                    </div>
                    <div style="border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">Approval Date</div>
                        <div style="font-size:12px;font-weight:500;color:var(--text)">
                            {{ $leave->approved_at?->format('M j, Y \a\t h:i A') ?? '—' }}
                        </div>
                    </div>
                </div>
            @else
                <div style="text-align:center;padding:20px 0;color:var(--text3)">
                    <div style="font-size:24px;margin-bottom:8px">⏳</div>
                    <div style="font-size:12px;font-weight:500">Awaiting Approval</div>
                </div>
            @endif
        </div>
    </div>

</div>

</x-app-layout>
