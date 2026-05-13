<x-app-layout title="My Performance" crumb="HR · Performance · Mine">

<div class="page-header">
    <div>
        <div class="page-header-title">My Performance</div>
        <div class="page-header-sub">Your evaluation history</div>
    </div>
</div>

<div class="section-card" style="margin-bottom:14px">
    <div style="padding:12px 16px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;border-bottom:1px solid var(--border)">
        <span class="fb-label">Rating Guide</span>
        <div class="fb-sep"></div>
        <span class="sp sp-ok"><span class="d"></span>Outstanding (9.0–10)</span>
        <span class="sp sp-rev"><span class="d"></span>Satisfactory (7.0–8.9)</span>
        <span class="sp sp-late"><span class="d"></span>Needs Improvement (5.0–6.9)</span>
        <span class="sp sp-no"><span class="d"></span>Poor (&lt;5.0)</span>
    </div>
</div>

<div class="section-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Score</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                    <th>Reviewed By</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr>
                    <td class="td-bold">{{ $r->period }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:7px">
                            <div class="p-bar"><div class="p-fill" style="width:{{ $r->score_pct ?? 0 }}%"></div></div>
                            <span class="td-bold">{{ $r->score }}</span>
                        </div>
                    </td>
                    <td>
                        @php
                        $sc = match($r->rating) {
                            'Outstanding'       => 'sp-ok',
                            'Satisfactory'      => 'sp-rev',
                            'Needs Improvement' => 'sp-late',
                            default             => 'sp-no',
                        };
                        @endphp
                        <span class="sp {{ $sc }}"><span class="d"></span>{{ $r->rating }}</span>
                    </td>
                    <td class="td-muted" style="max-width:220px">{{ $r->feedback ?? '—' }}</td>
                    <td class="td-muted">{{ $r->reviewer?->name }}</td>
                    <td class="td-muted">{{ $r->created_at->format('M j, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    No performance reviews yet
                </div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
        <div class="pagination-wrap">{{ $records->links() }}</div>
    @endif
</div>

</x-app-layout>
