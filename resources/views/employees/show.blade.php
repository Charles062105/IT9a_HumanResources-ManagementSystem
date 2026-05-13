<x-app-layout title="Employee Profile" crumb="HR · Employees · Profile">

<div class="page-header">
    <div>
        <div class="page-header-title">{{ $employee->full_name }}</div>
        <div class="page-header-sub">{{ $employee->employee_id }} · {{ $employee->department }} · {{ $employee->position }}</div>
    </div>
    <div style="display:flex;gap:8px">
        @if(auth()->user()->isAdmin())
        <a href="{{ route('employees.edit', $employee) }}" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:7px;font-size:12px">Edit</a>
        @endif
        <a href="{{ route('employees.index') }}" class="btn-secondary">← Back</a>
    </div>
</div>

<div class="profile-grid">

    {{-- Profile Card --}}
    <div class="card">
        <div class="card-body" style="text-align:center;padding:24px">
            <div class="av" style="width:64px;height:64px;background:#1A4D8F;color:#fff;font-size:22px;font-weight:700;margin:0 auto 12px;border-radius:50%">
                {{ $employee->initials }}
            </div>
            <div style="font-size:15px;font-weight:600;color:var(--text)">{{ $employee->full_name }}</div>
            <div style="font-size:12px;color:var(--text3);margin-top:3px">{{ $employee->position }}</div>
            <div style="font-size:12px;color:var(--text3)">{{ $employee->department }}</div>
            <div style="margin:14px 0">
                @php $sc = match($employee->status) { 'active' => 'sp-ok', 'probationary' => 'sp-prob', 'contractual' => 'sp-cont', default => 'sp-no' }; @endphp
                <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst($employee->status) }}</span>
            </div>
            <div style="font-size:11px;color:var(--text3)">{{ $employee->years_of_service }} year{{ $employee->years_of_service != 1 ? 's' : '' }} of service</div>
        </div>
        <div style="border-top:1px solid var(--border);padding:14px 18px">
            @php $fields = [['Employee ID','employee_id'],['Email','email'],['Phone','phone'],['Date Hired','date_hired'],['Date of Birth','date_of_birth'],['Contract Expiry','contract_expiry']]; @endphp
            @foreach($fields as [$label, $field])
            <div style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid var(--border);font-size:11px">
                <span style="color:var(--text3)">{{ $label }}</span>
                <span style="font-weight:500;color:var(--text)">
                    @if(in_array($field,['date_hired','date_of_birth','contract_expiry']))
                        {{ $employee->$field?->format('M j, Y') ?? '—' }}
                    @else
                        {{ $employee->$field ?? '—' }}
                    @endif
                </span>
            </div>
            @endforeach
        </div>
        <div style="border-top:1px solid var(--border);padding:14px 18px">
            <div style="font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px">Government IDs</div>
            @foreach([['SSS','sss_number'],['Pag-IBIG','pagibig_number'],['PhilHealth','philhealth_number']] as [$l,$f])
            <div style="display:flex;justify-content:space-between;padding:4px 0;font-size:11px">
                <span style="color:var(--text3)">{{ $l }}</span>
                <span style="font-weight:500;font-family:monospace;font-size:10px;color:var(--text)">{{ $employee->$f ?? '—' }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Stats & History --}}
    <div style="display:flex;flex-direction:column;gap:14px">

        {{-- Quick Stats --}}
        <div class="stats-grid">
            <div class="kpi">
                <div class="kpi-label">Attendance (30d)</div>
                <div class="kpi-num">{{ $employee->attendances->where('date','>=',now()->subDays(30))->whereIn('status',['present','late'])->count() }}</div>
                <div class="kpi-sub">Days present</div>
            </div>
            <div class="kpi">
                <div class="kpi-label">Violations</div>
                <div class="kpi-num" style="{{ $employee->violations->where('status','open')->count() > 0 ? 'color:var(--danger)' : '' }}">
                    {{ $employee->violations->where('status','open')->count() }}
                </div>
                <div class="kpi-sub">Open cases</div>
            </div>
            <div class="kpi">
                <div class="kpi-label">Latest Score</div>
                <div class="kpi-num">{{ $employee->performances->first()?->score ?? '—' }}</div>
                <div class="kpi-sub">{{ $employee->performances->first()?->period ?? 'No reviews' }}</div>
            </div>
        </div>

        {{-- Recent Attendance --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Recent Attendance</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Date</th><th>Time In</th><th>Time Out</th><th>Hours</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($employee->attendances->sortByDesc('date')->take(5) as $a)
                        <tr>
                            <td class="td-muted">{{ $a->date->format('M j, Y') }}</td>
                            <td class="td-muted">{{ $a->time_in?->format('h:i A') ?? '—' }}</td>
                            <td class="td-muted">{{ $a->time_out?->format('h:i A') ?? '—' }}</td>
                            <td class="td-bold">{{ $a->hours_worked ? $a->hours_worked.'h' : '—' }}</td>
                            <td>
                                @php $sc = match($a->status){ 'present'=>'sp-ok','late'=>'sp-late','absent'=>'sp-no','on_leave'=>'sp-lv',default=>'sp-pend' }; @endphp
                                <span class="sp {{ $sc }}"><span class="d"></span>{{ ucfirst(str_replace('_',' ',$a->status)) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state" style="padding:16px">No attendance records</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Violations --}}
        @if($employee->violations->count())
        <div class="card">
            <div class="card-header"><span class="card-title">Violations</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Level</th><th>Offense</th><th>#</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @foreach($employee->violations->sortByDesc('date')->take(5) as $v)
                        @php $b = $v->level_badge_color; @endphp
                        <tr>
                            <td><span class="sp" style="background:{{ $b['bg'] }};color:{{ $b['text'] }};border-radius:5px">{{ $v->level }}</span></td>
                            <td class="td-muted">{{ $v->offense }}</td>
                            <td style="text-align:center"><span class="vb" style="background:{{ $b['bg'] }};color:{{ $b['text'] }}">{{ $v->offense_count }}</span></td>
                            <td><span class="sp {{ $v->status==='open'?'sp-open':'sp-ok' }}"><span class="d"></span>{{ ucfirst($v->status) }}</span></td>
                            <td class="td-muted">{{ $v->date?->format('M j') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>

</x-app-layout>
