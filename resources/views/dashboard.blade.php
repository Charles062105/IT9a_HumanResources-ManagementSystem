<x-app-layout title="Dashboard" crumb="Overview · Your Workspace">

    <div class="page-header">
        <div>
            <div class="page-header-title">Dashboard</div>
            <div class="page-header-sub">{{ now()->format('l, F j, Y') }} • Welcome back, {{ auth()->user()->name }}</div>
        </div>
    </div>

    {{-- Quick Stats Row --}}
    <div class="kpi-row" style="margin-bottom: 20px;">
        @if(auth()->user()->isAdmin())
            {{-- Total Employees --}}
            <div class="kpi">
                <div class="kpi-label">
                    <div class="ki" style="background: #DBEAFE; color: #1E40AF;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    Total Employees
                </div>
                <div class="kpi-num">{{ \App\Models\Employee::where('status', 'active')->count() }}</div>
            </div>

            {{-- Present Today --}}
            <div class="kpi">
                <div class="kpi-label">
                    <div class="ki" style="background: #DCFCE7; color: #16A34A;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    Present Today
                </div>
                <div class="kpi-num" style="color: #16A34A;">{{ \App\Models\Attendance::whereDate('date', now())->where('status', 'present')->count() }}</div>
            </div>

            {{-- Absent Today --}}
            <div class="kpi">
                <div class="kpi-label">
                    <div class="ki" style="background: #FEE2E2; color: #DC2626;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    Absent Today
                </div>
                <div class="kpi-num" style="color: #DC2626;">{{ \App\Models\Attendance::whereDate('date', now())->where('status', 'absent')->count() }}</div>
            </div>

            {{-- Pending Leaves --}}
            <div class="kpi">
                <div class="kpi-label">
                    <div class="ki" style="background: #FEF3C7; color: #D97706;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                    </div>
                    Pending Leaves
                </div>
                <div class="kpi-num" style="color: #D97706;">{{ \App\Models\Leave::where('status', 'pending')->count() }}</div>
            </div>
        @else
            {{-- Employee: My Attendance --}}
            <div class="kpi">
                <div class="kpi-label">
                    <div class="ki" style="background: #DBEAFE; color: #1E40AF;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    Today's Status
                </div>
                <div class="kpi-num">
                    @php
                        $today = \App\Models\Attendance::whereDate('date', now())
                            ->where('employee_id', auth()->user()->employee?->id)
                            ->first();
                        echo $today ? ucfirst(str_replace('_', ' ', $today->status)) : 'Not Recorded';
                    @endphp
                </div>
            </div>

            {{-- My Leave Balance --}}
            <div class="kpi">
                <div class="kpi-label">
                    <div class="ki" style="background: #EDE9FE; color: #6D28D9;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        </svg>
                    </div>
                    Available Leaves
                </div>
                <div class="kpi-num" style="color: #6D28D9;">
                    @php
                        $emp = auth()->user()->employee;
                        echo $emp ? ($emp->leave_balance ?? '—') : '—';
                    @endphp
                </div>
            </div>

            {{-- My Performance --}}
            <div class="kpi">
                <div class="kpi-label">
                    <div class="ki" style="background: #FECACA; color: #991B1B;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                    </div>
                    Performance Score
                </div>
                <div class="kpi-num" style="color: #991B1B;">
                    @php
                        $perf = \App\Models\Performance::where('employee_id', auth()->user()->employee?->id)
                            ->latest()
                            ->first();
                        echo $perf ? ($perf->score ?? '—') : '—';
                    @endphp
                </div>
            </div>
        @endif
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
        {{-- Quick Links --}}
        <div class="section-card">
            <div style="padding: 20px; border-bottom: 1px solid var(--border);">
                <div style="font-size: 12px; font-weight: 600; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px;">Quick Actions</div>
            </div>
            <div style="padding: 16px; display: flex; flex-direction: column; gap: 10px;">
                @if(auth()->user()->isEmployee())
                    <a href="{{ route('attendance.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 8px; background: var(--surface2); text-decoration: none; color: var(--text); transition: all 0.2s; border: 1px solid transparent;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <div style="flex: 1;">
                            <div style="font-size: 12px; font-weight: 600; color: var(--text);">Time Tracking</div>
                            <div style="font-size: 11px; color: var(--text3);">Clock in or out</div>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                    <a href="{{ route('leaves.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 8px; background: var(--surface2); text-decoration: none; color: var(--text); transition: all 0.2s; border: 1px solid transparent;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        <div style="flex: 1;">
                            <div style="font-size: 12px; font-weight: 600; color: var(--text);">My Leaves</div>
                            <div style="font-size: 11px; color: var(--text3);">Request or view leaves</div>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                    <a href="{{ route('performance.my') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 8px; background: var(--surface2); text-decoration: none; color: var(--text); transition: all 0.2s; border: 1px solid transparent;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6D28D9" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                        <div style="flex: 1;">
                            <div style="font-size: 12px; font-weight: 600; color: var(--text);">My Performance</div>
                            <div style="font-size: 11px; color: var(--text3);">View your reviews</div>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('employees.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 8px; background: var(--surface2); text-decoration: none; color: var(--text); transition: all 0.2s; border: 1px solid transparent;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        <div style="flex: 1;">
                            <div style="font-size: 12px; font-weight: 600; color: var(--text);">Manage Employees</div>
                            <div style="font-size: 11px; color: var(--text3);">View all employee records</div>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                    <a href="{{ route('attendance.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 8px; background: var(--surface2); text-decoration: none; color: var(--text); transition: all 0.2s; border: 1px solid transparent;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <div style="flex: 1;">
                            <div style="font-size: 12px; font-weight: 600; color: var(--text);">Attendance Records</div>
                            <div style="font-size: 11px; color: var(--text3);">Track attendance & hours</div>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                    <a href="{{ route('leaves.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 8px; background: var(--surface2); text-decoration: none; color: var(--text); transition: all 0.2s; border: 1px solid transparent;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6D28D9" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        </svg>
                        <div style="flex: 1;">
                            <div style="font-size: 12px; font-weight: 600; color: var(--text);">Manage Leaves</div>
                            <div style="font-size: 11px; color: var(--text3);">Approve or reject requests</div>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="section-card">
            <div style="padding: 20px; border-bottom: 1px solid var(--border);">
                <div style="font-size: 12px; font-weight: 600; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px;">System Info</div>
            </div>
            <div style="padding: 20px; display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <div style="font-size: 11px; color: var(--text3); margin-bottom: 4px;">Current User</div>
                    <div style="font-size: 13px; font-weight: 600; color: var(--text);">{{ auth()->user()->name }}</div>
                    <div style="font-size: 11px; color: var(--text3); margin-top: 2px;">
                        Role: <span style="font-weight: 500; color: var(--text);">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
                    </div>
                </div>
                <div style="height: 1px; background: var(--border);"></div>
                <div>
                    <div style="font-size: 11px; color: var(--text3); margin-bottom: 4px;">Last Login</div>
                    <div style="font-size: 13px; font-weight: 600; color: var(--text);">{{ now()->format('M j, Y · h:i A') }}</div>
                </div>
                <div style="height: 1px; background: var(--border);"></div>
                <div>
                    <div style="font-size: 11px; color: var(--text3); margin-bottom: 4px;">System Status</div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <div style="width: 8px; height: 8px; background: #16A34A; border-radius: 50%; animation: pulse 2s infinite;"></div>
                        <div style="font-size: 13px; font-weight: 600; color: #16A34A;">Operational</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Help & Onboarding --}}
    <div class="section-card">
        <div style="padding: 20px; border-bottom: 1px solid var(--border);">
            <div style="font-size: 12px; font-weight: 600; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px;">Getting Started</div>
        </div>
        <div style="padding: 20px;">
            <p style="font-size: 13px; color: var(--text2); margin: 0 0 16px 0; line-height: 1.6;">
                Welcome to HRMS Pro! This system helps manage attendance, leaves, performance reviews, and employee records.
                @if(auth()->user()->isAdmin())
                    Use the sidebar to navigate between modules and manage your organization's HR operations.
                @else
                    Use the quick actions above to access your time tracking, leaves, and performance information.
                @endif
            </p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div style="padding: 12px; background: var(--surface2); border-radius: 8px; border-left: 3px solid #2563EB;">
                    <div style="font-size: 11px; font-weight: 600; color: var(--text); margin-bottom: 4px;">📌 Tip</div>
                    <div style="font-size: 11px; color: var(--text3);">Use the sidebar menu to navigate between all modules</div>
                </div>
                <div style="padding: 12px; background: var(--surface2); border-radius: 8px; border-left: 3px solid #D97706;">
                    <div style="font-size: 11px; font-weight: 600; color: var(--text); margin-bottom: 4px;">⏰ Remember</div>
                    <div style="font-size: 11px; color: var(--text3);">Check your attendance daily before end of shift</div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
