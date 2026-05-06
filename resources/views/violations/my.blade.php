<x-app-layout title="My Violations" crumb="HR · Violations · Mine">

<div class="page-header">
    <div>
        <div class="page-header-title">My Violations</div>
        <div class="page-header-sub">Your disciplinary record</div>
    </div>
</div>

<div class="section-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Offense</th>
                    <th>Offense #</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Issued By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($violations as $v)
                @php $badge = $v->level_badge_color; @endphp
                <tr>
                    <td>
                        <span class="sp" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border-radius:5px">{{ $v->level }}</span>
                    </td>
                    <td class="td-muted">{{ $v->offense }}</td>
                    <td style="text-align:center">
                        <span class="vb" style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }}">{{ $v->offense_count }}</span>
                    </td>
                    <td>
                        <span class="sp {{ $v->status === 'open' ? 'sp-open' : 'sp-ok' }}">
                            <span class="d"></span>{{ ucfirst($v->status) }}
                        </span>
                    </td>
                    <td class="td-muted">{{ $v->date?->format('M j, Y') }}</td>
                    <td class="td-muted">{{ $v->issuer?->name ?? 'Admin' }}</td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg>
                    No violations on record
                </div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($violations->hasPages())
        <div class="pagination-wrap">{{ $violations->links() }}</div>
    @endif
</div>

</x-app-layout>
