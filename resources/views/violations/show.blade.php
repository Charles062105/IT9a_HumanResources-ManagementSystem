<x-app-layout title="Violation Details" crumb="HR · Disciplinary tracker">

<div class="page-header">
    <div>
        <div class="page-header-title">Violation Details</div>
        <div class="page-header-sub">Disciplinary record and resolution tracking</div>
    </div>
    <a href="{{ route('violations.index') }}" class="btn-secondary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:7px;font-size:12px">
        ← Back
    </a>
</div>

<div class="section-card">
    @php $badge = $violation->level_badge_color; @endphp
    
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin-bottom:24px">
        <!-- Employee Info -->
        <div>
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Employee</div>
            <div style="font-size:15px;font-weight:600;color:#1F2937">{{ $violation->employee?->full_name }}</div>
            <div style="font-size:12px;color:#6B7280;margin-top:4px">{{ $violation->employee?->department }}</div>
        </div>

        <!-- Violation Level -->
        <div>
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Discipline Level</div>
            <span class="sp" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border-radius:5px;display:inline-block;padding:6px 12px;font-weight:500">
                {{ $violation->level }}
            </span>
        </div>

        <!-- Status -->
        <div>
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Status</div>
            @php $sc = $violation->status === 'open' ? 'sp-open' : 'sp-ok'; @endphp
            <span class="sp {{ $sc }}" style="display:inline-flex;align-items:center;gap:6px">
                <span class="d"></span>{{ ucfirst($violation->status) }}
            </span>
        </div>

        <!-- Offense Count -->
        <div>
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Offense #</div>
            <span class="vb" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};display:inline-block;padding:4px 10px;border-radius:5px;font-weight:600">
                {{ $violation->offense_count }}
            </span>
        </div>

        <!-- Date -->
        <div>
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Date</div>
            <div style="font-size:14px;font-weight:500;color:#1F2937">{{ $violation->date?->format('M j, Y') }}</div>
        </div>

        <!-- Issued By -->
        <div>
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px">Issued By</div>
            <div style="font-size:14px;font-weight:500;color:#1F2937">{{ $violation->issuer?->name }}</div>
        </div>
    </div>

    <div style="border-top:1px solid #E5E7EB;padding-top:20px">
        <!-- Offense -->
        <div style="margin-bottom:20px">
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px">Offense</div>
            <div style="font-size:14px;color:#1F2937;font-weight:500">{{ $violation->offense }}</div>
        </div>

        <!-- Description -->
        @if($violation->description)
        <div style="margin-bottom:20px">
            <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px">Description</div>
            <div style="font-size:13px;color:#4B5563;line-height:1.6;white-space:pre-wrap">{{ $violation->description }}</div>
        </div>
        @endif
    </div>

    <!-- Actions -->
    @if(auth()->user()->isAdmin())
    <div style="border-top:1px solid #E5E7EB;padding-top:20px;margin-top:20px;display:flex;gap:12px">
        @if($violation->status === 'open')
        <form method="POST" action="{{ route('violations.resolve', $violation) }}" style="display:inline">
            @csrf @method('PATCH')
            <button type="submit" class="btn-primary" style="padding:8px 16px;border-radius:7px;font-size:12px;border:none;cursor:pointer">
                Mark as Resolved
            </button>
        </form>
        @endif

        <form method="POST" action="{{ route('violations.destroy', $violation) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this violation?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger" style="padding:8px 16px;border-radius:7px;font-size:12px;border:none;cursor:pointer;background-color:#FEE2E2;color:#991B1B">
                Delete
            </button>
        </form>
    </div>
    @endif
</div>

</x-app-layout>
