<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>HRMS — Human Resources Management System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
<style>
/* ────────────────────────────────────────────────────────────
   HRMS — Landing Page Stylesheet
   ──────────────────────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --navy:       #0F1E38;
    --navy2:      #162847;
    --bg:         #F0F2F7;
    --surface:    #FFFFFF;
    --surface2:   #F7F8FB;
    --text:       #0F1E38;
    --text2:      #475569;
    --text3:      #94A3B8;
    --border:     rgba(15,30,56,0.07);
    --border2:    rgba(15,30,56,0.13);
    --indigo:     #2563EB;
    --indigo-lt:  #DBEAFE;
    --indigo-dk:  #1E40AF;
    --green:      #16A34A;
    --green-lt:   #DCFCE7;
    --amber:      #D97706;
    --amber-lt:   #FEF3C7;
    --red:        #DC2626;
    --red-lt:     #FEE2E2;
    --radius:     14px;
    --radius-sm:  8px;
}

html { scroll-behavior: smooth; }
body {
    font-family: 'DM Sans', sans-serif;
    background: #fff;
    color: var(--text);
    font-size: 14px;
    line-height: 1.6;
    overflow-x: hidden;
}

/* ── UTILITIES ── */
.btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-family: 'DM Sans', sans-serif; font-weight: 500;
    cursor: pointer; border: none; text-decoration: none;
    transition: all 0.15s; font-size: 13px; line-height: 1;
}
.btn svg { width: 14px; height: 14px; flex-shrink: 0; }
.btn-sm  { padding: 7px 14px; border-radius: 7px; font-size: 12px; }
.btn-lg  { padding: 11px 22px; border-radius: 9px; font-size: 14px; }
.btn-primary   { background: var(--navy); color: #fff; }
.btn-primary:hover { opacity: 0.87; }
.btn-secondary { background: var(--surface2); color: var(--text); border: 1px solid var(--border2); }
.btn-secondary:hover { background: #e8eaee; }
.btn-white     { background: #fff; color: var(--navy); }
.btn-white:hover { background: #eceff5; }
.btn-outline-w { background: rgba(255,255,255,0.12); color: #fff; border: 1px solid rgba(255,255,255,0.25); }
.btn-outline-w:hover { background: rgba(255,255,255,0.2); }
.w-full { width: 100%; justify-content: center; }

/* ── NAVBAR ── */
.l-nav {
    position: sticky; top: 0; z-index: 100;
    height: 60px;
    background: rgba(255,255,255,0.94);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 52px;
}
.l-brand { display: flex; align-items: center; gap: 9px; }
.l-brand-icon {
    width: 32px; height: 32px;
    background: var(--navy); border-radius: 8px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.l-brand-icon svg { width: 16px; height: 16px; stroke: #fff; stroke-width: 2; }
.l-brand-name {
    font-family: 'Syne', sans-serif;
    font-size: 16px; font-weight: 700; color: var(--navy); letter-spacing: -0.3px;
}
.l-nav-links { display: flex; gap: 28px; }
.l-nav-link {
    font-size: 13px; color: var(--text2);
    text-decoration: none; font-weight: 400; transition: color 0.15s;
}
.l-nav-link:hover { color: var(--navy); }
.l-nav-ctas { display: flex; gap: 8px; align-items: center; }

/* ── HERO ── */
.hero {
    background: var(--navy);
    min-height: 580px;
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 48px;
    align-items: center;
    padding: 80px 52px 88px;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 60% 80% at 80% 50%, rgba(37,99,235,0.18) 0%, transparent 70%);
    pointer-events: none;
}
.hero-content { position: relative; }
.hero-badge {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 20px;
    padding: 5px 13px;
    font-size: 11px; color: rgba(255,255,255,0.7);
    margin-bottom: 22px; font-weight: 500; letter-spacing: 0.3px;
}
.hero-badge-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #4ADE80; flex-shrink: 0;
    animation: pulse 2s infinite;
}
.hero h1 {
    font-family: 'Syne', sans-serif;
    font-size: 50px; font-weight: 800; color: #fff;
    line-height: 1.08; letter-spacing: -1.5px; margin-bottom: 20px;
}
.hero h1 span { color: #60A5FA; }
.hero-desc {
    font-size: 15px; color: rgba(255,255,255,0.52);
    line-height: 1.8; margin-bottom: 30px; max-width: 440px;
}
.hero-ctas { display: flex; gap: 10px; flex-wrap: wrap; }

/* ── HERO CARD ── */
.hero-visual { position: relative; }
.hero-card {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 16px; padding: 22px;
}
.hv-label {
    font-size: 10px; font-weight: 600;
    color: rgba(255,255,255,0.32);
    text-transform: uppercase; letter-spacing: 0.7px;
    margin-bottom: 14px;
}
.hv-stats {
    display: grid; grid-template-columns: repeat(2,1fr);
    gap: 10px; margin-bottom: 20px;
}
.hv-stat {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 10px; padding: 12px;
}
.hv-val {
    font-family: 'Syne', sans-serif;
    font-size: 22px; font-weight: 700; color: #fff; letter-spacing: -0.5px;
}
.hv-name { font-size: 10px; color: rgba(255,255,255,0.38); margin-top: 2px; }
.hv-chart-label {
    font-size: 10px; color: rgba(255,255,255,0.28);
    text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;
}
.hv-bars {
    display: flex; gap: 5px; align-items: flex-end;
    height: 54px; margin-bottom: 16px;
}
.hv-bar-b { flex: 1; border-radius: 3px; transition: height 0.3s; }
.hv-divider { height: 1px; background: rgba(255,255,255,0.06); margin: 14px 0; }
.hv-footer { display: flex; align-items: center; justify-content: space-between; }
.hv-live {
    display: flex; align-items: center; gap: 5px;
    font-size: 10px; color: rgba(255,255,255,0.38);
}
.hv-live-dot {
    width: 6px; height: 6px; border-radius: 50%; background: #4ADE80;
    animation: pulse 2s infinite;
}
.hv-time {
    font-size: 11px; color: rgba(255,255,255,0.32);
    font-variant-numeric: tabular-nums;
}

/* ── FEATURES ── */
.l-section { padding: 80px 52px; }
.l-section-alt { background: var(--bg); }
.l-inner { max-width: 1100px; margin: 0 auto; }
.l-label {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 10px; font-weight: 600;
    color: var(--indigo); text-transform: uppercase; letter-spacing: 0.8px;
    margin-bottom: 10px;
}
.l-label::before { content: ''; width: 16px; height: 2px; background: var(--indigo); border-radius: 1px; }
.l-title {
    font-family: 'Syne', sans-serif;
    font-size: 30px; font-weight: 700; color: var(--text);
    letter-spacing: -0.5px; line-height: 1.2; margin-bottom: 10px;
}
.l-desc {
    font-size: 14px; color: var(--text3);
    max-width: 500px; line-height: 1.75; margin-bottom: 40px;
}
.feat-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
.feat-card {
    background: #fff; border: 1px solid var(--border);
    border-radius: var(--radius); padding: 22px;
    transition: border-color 0.15s, transform 0.15s;
}
.feat-card:hover { border-color: var(--border2); transform: translateY(-2px); }
.feat-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 14px; flex-shrink: 0;
}
.feat-icon svg { width: 18px; height: 18px; stroke-width: 2; }
.fi-in { background: #DBEAFE; } .fi-in svg { stroke: #1E40AF; }
.fi-cy { background: #DCFCE7; } .fi-cy svg { stroke: #166534; }
.fi-gr { background: #FEF3C7; } .fi-gr svg { stroke: #92400E; }
.fi-am { background: #EDE9FE; } .fi-am svg { stroke: #5B21B6; }
.fi-rd { background: #FEE2E2; } .fi-rd svg { stroke: #991B1B; }
.fi-bl { background: #E0F2FE; } .fi-bl svg { stroke: #0C4A6E; }
.feat-card h3 {
    font-family: 'Syne', sans-serif;
    font-size: 14px; font-weight: 600; color: var(--text);
    margin-bottom: 7px; letter-spacing: -0.1px;
}
.feat-card p { font-size: 12px; color: var(--text3); line-height: 1.75; }

/* ── STATS ── */
.stats-row {
    display: grid; grid-template-columns: repeat(4,1fr);
    border: 1px solid var(--border); border-radius: var(--radius);
    overflow: hidden; background: #fff;
}
.stats-item { padding: 36px 24px; text-align: center; border-right: 1px solid var(--border); }
.stats-item:last-child { border-right: none; }
.stats-num {
    font-family: 'Syne', sans-serif;
    font-size: 38px; font-weight: 800;
    color: var(--navy); letter-spacing: -1px; margin-bottom: 5px;
}
.stats-lbl { font-size: 12px; color: var(--text3); }

/* ── ABOUT ── */
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
.check-list { display: flex; flex-direction: column; gap: 12px; }
.check-item {
    display: flex; gap: 12px; padding: 16px;
    background: #fff; border-radius: 10px; border: 1px solid var(--border);
}
.check-mark {
    width: 22px; height: 22px; border-radius: 50%;
    background: var(--indigo-lt); color: var(--indigo-dk);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; flex-shrink: 0; margin-top: 1px;
}
.check-item-title { font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 3px; }
.check-item-desc  { font-size: 11px; color: var(--text3); line-height: 1.6; }

/* ── CTA ── */
.l-cta { background: var(--navy); padding: 80px 52px; text-align: center; }
.l-cta h2 {
    font-family: 'Syne', sans-serif;
    font-size: 32px; font-weight: 800; color: #fff;
    margin-bottom: 10px; letter-spacing: -0.5px;
}
.l-cta p { font-size: 14px; color: rgba(255,255,255,0.48); margin-bottom: 28px; }
.l-cta-btns { display: flex; gap: 10px; justify-content: center; }

/* ── FOOTER ── */
.l-footer {
    padding: 20px 52px; border-top: 1px solid var(--border);
    text-align: center; font-size: 11px; color: var(--text3);
}

/* ── AUTH MODAL ── */
.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(10,18,35,0.6);
    backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    z-index: 999;
    opacity: 0; pointer-events: none;
    transition: opacity 0.2s;
}
.modal-overlay.open { opacity: 1; pointer-events: auto; }
.modal-box {
    background: #fff; border-radius: 16px; padding: 28px;
    width: 100%; max-width: 420px; position: relative;
    transform: translateY(16px);
    transition: transform 0.22s;
    box-shadow: 0 24px 64px rgba(0,0,0,0.16);
}
.modal-overlay.open .modal-box { transform: translateY(0); }

.modal-close {
    position: absolute; top: 16px; right: 16px;
    width: 28px; height: 28px; border-radius: 6px;
    background: var(--surface2); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--text3); transition: all 0.12s;
}
.modal-close:hover { background: #dde0e8; color: var(--text); }

.modal-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.modal-brand-icon {
    width: 32px; height: 32px; background: var(--navy);
    border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.modal-brand-name { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; color: var(--navy); }
.modal-brand-sub  { font-size: 10px; color: var(--text3); }

.modal-tabs {
    display: flex; gap: 2px;
    background: var(--surface2);
    border-radius: 8px; padding: 3px; margin-bottom: 22px;
}
.modal-tab {
    flex: 1; padding: 7px; border-radius: 6px; border: none;
    background: transparent; font-size: 12px; font-weight: 500;
    color: var(--text3); cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: all 0.15s;
}
.modal-tab.active { background: #fff; color: var(--text); box-shadow: 0 1px 3px rgba(0,0,0,0.08); }

.modal-panel { display: none; }
.modal-panel.active { display: block; }
.modal-title { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 4px; letter-spacing: -0.3px; }
.modal-sub    { font-size: 12px; color: var(--text3); margin-bottom: 18px; line-height: 1.6; }

.form-grid    { display: flex; flex-direction: column; gap: 12px; }
.form-group   { display: flex; flex-direction: column; gap: 5px; }
.form-label   { font-size: 11px; font-weight: 500; color: var(--text2); }
.form-control {
    height: 36px;
    background: var(--surface2); border: 1px solid var(--border2);
    border-radius: 7px; padding: 0 10px;
    font-size: 12px; color: var(--text); font-family: 'DM Sans', sans-serif;
    outline: none; transition: border 0.12s; width: 100%;
}
.form-control:focus { border-color: rgba(37,99,235,0.5); background: #fff; }
.form-control.err   { border-color: rgba(220,38,38,0.5); }

.alert {
    display: flex; align-items: flex-start; gap: 7px;
    border-radius: 7px; padding: 9px 11px;
    font-size: 11px; line-height: 1.55; margin-bottom: 12px;
}
.alert svg { width: 13px; height: 13px; flex-shrink: 0; margin-top: 1px; }
.alert-warning { background: var(--amber-lt); color: #92400E; }
.alert-danger  { background: var(--red-lt);   color: #991B1B; }
.alert-success { background: var(--green-lt);  color: #166534; }

.remember-row { display: flex; align-items: center; gap: 7px; }
.remember-row label { font-size: 11px; color: var(--text3); cursor: pointer; }
.remember-row input { width: 14px; height: 14px; accent-color: var(--indigo); cursor: pointer; }

.modal-footer-text { text-align: center; font-size: 11px; color: var(--text3); margin-top: 14px; }
.modal-footer-text a { color: var(--indigo); text-decoration: none; font-weight: 500; }
.admin-note { font-size: 10px; color: var(--text3); text-align: center; margin-top: 6px; line-height: 1.5; }

@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
    .hero { grid-template-columns: 1fr; }
    .hero-visual { display: none; }
    .feat-grid { grid-template-columns: 1fr 1fr; }
    .about-grid { grid-template-columns: 1fr; gap: 32px; }
    .stats-row { grid-template-columns: repeat(2,1fr); }
    .stats-item:nth-child(2) { border-right: none; }
    .stats-item:nth-child(3) { border-top: 1px solid var(--border); }
    .stats-item:nth-child(4) { border-top: 1px solid var(--border); }
    .l-nav { padding: 0 24px; }
    .l-nav-links { display: none; }
    .l-section, .l-cta { padding: 56px 24px; }
    .hero { padding: 56px 24px 64px; }
}
</style>
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav class="l-nav" id="landingNav">
    <div class="l-brand">
        <div class="l-brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <span class="l-brand-name">HRMS</span>
    </div>
    <div class="l-nav-links">
        <a href="#features" class="l-nav-link">Features</a>
        <a href="#stats" class="l-nav-link">Stats</a>
        <a href="#about" class="l-nav-link">About</a>
    </div>
    <div class="l-nav-ctas">
        <a href="#" data-auth="login" class="btn btn-secondary btn-sm">Sign In</a>
        <a href="#" data-auth="register" class="btn btn-primary btn-sm">Get Started</a>
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">
            <div class="hero-badge-dot"></div>
            Philippine HR Management
        </div>
        <h1>Manage your<br>people, <span>smarter</span><br>not harder.</h1>
        <p class="hero-desc">A modern, all-in-one HR platform for Philippine organizations. Employee records, attendance, leave management, and DOLE compliance — all in one place.</p>
        <div class="hero-ctas">
            <a href="#" data-auth="register" class="btn btn-white btn-lg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Get Started Free
            </a>
            <a href="#features" class="btn btn-outline-w btn-lg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Learn More
            </a>
        </div>
    </div>

    <div class="hero-visual">
        <div class="hero-card">
            <div class="hv-label">Dashboard Overview</div>
            <div class="hv-stats">
                <div class="hv-stat"><div class="hv-val">248</div><div class="hv-name">Employees</div></div>
                <div class="hv-stat"><div class="hv-val">97%</div><div class="hv-name">Attendance</div></div>
                <div class="hv-stat"><div class="hv-val">12</div><div class="hv-name">On Leave</div></div>
                <div class="hv-stat"><div class="hv-val">3</div><div class="hv-name">Pending</div></div>
            </div>
            <div class="hv-chart-label">Weekly Attendance</div>
            <div class="hv-bars" id="heroBars"></div>
            <div class="hv-divider"></div>
            <div class="hv-footer">
                <div class="hv-live"><div class="hv-live-dot"></div>Live</div>
                <div class="hv-time" id="heroTime">—</div>
            </div>
        </div>
    </div>
</section>

{{-- ── FEATURES ── --}}
<section class="l-section l-section-alt" id="features">
    <div class="l-inner">
        <div class="l-label">Core Features</div>
        <h2 class="l-title">Everything your HR team needs</h2>
        <p class="l-desc">Purpose-built for Philippine organizations with DOLE compliance and real government deduction standards.</p>
        <div class="feat-grid">
            <div class="feat-card">
                <div class="feat-icon fi-in"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                <h3>Employee Management</h3>
                <p>Complete employee profiles with personal info, job details, government numbers (SSS, Pag-IBIG, PhilHealth), emergency contacts, and full employment history.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon fi-cy"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <h3>Attendance Tracking</h3>
                <p>Real-time time-in/out with automatic late detection, absence tracking, and progressive discipline enforcement per DOLE guidelines.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon fi-gr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
                <h3>Leave Management</h3>
                <p>Employees apply online, admins approve instantly. All types covered — vacation, sick, emergency, maternity, paternity, and solo parent.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon fi-rd"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
                <h3>Progressive Discipline</h3>
                <p>5-level violation system: Verbal Warning → Written → Final → Suspension → Termination. Auto-tracked offense counts with full case history.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon fi-am"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                <h3>Performance Reviews</h3>
                <p>Quarterly and annual evaluations with 0–10 scoring, automatic rating labels, reviewer feedback, and personal performance history dashboards.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon fi-bl"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></div>
                <h3>Smart Notifications</h3>
                <p>Real-time alerts for approvals, violations, and announcements. Badge counts, read/unread states, and full notification history.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── STATS ── --}}
<section class="l-section" id="stats">
    <div class="l-inner">
        <div class="stats-row">
            <div class="stats-item">
                <div class="stats-num" data-target="500" data-suffix="+">0+</div>
                <div class="stats-lbl">Organizations</div>
            </div>
            <div class="stats-item">
                <div class="stats-num" data-target="12000" data-suffix="+">0+</div>
                <div class="stats-lbl">Employees Managed</div>
            </div>
            <div class="stats-item">
                <div class="stats-num" data-target="99" data-suffix="%">0%</div>
                <div class="stats-lbl">Uptime</div>
            </div>
            <div class="stats-item">
                <div class="stats-num" data-target="24" data-suffix="/7">0/7</div>
                <div class="stats-lbl">Support</div>
            </div>
        </div>
    </div>
</section>

{{-- ── ABOUT ── --}}
<section class="l-section l-section-alt" id="about">
    <div class="l-inner">
        <div class="about-grid">
            <div>
                <div class="l-label">About HRMS</div>
                <h2 class="l-title">Built for the Filipino workplace</h2>
                <p style="color:var(--text3);line-height:1.8;margin-bottom:14px;font-size:13px">HRMS handles the unique demands of Philippine HR — DOLE labor code compliance, SSS/PhilHealth/Pag-IBIG contribution tracking, and the 5-level progressive discipline framework required by law.</p>
                <p style="color:var(--text3);line-height:1.8;margin-bottom:30px;font-size:13px">Admin approval for new accounts ensures security. Employees get personal dashboards with their own attendance, leave, and violation history.</p>
                <a href="#" data-auth="register" class="btn btn-primary btn-lg">
                    Start for free
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
            <div class="check-list">
                @foreach([
                    ['Admin approval for all accounts','No unauthorized access — all registrations require admin review before login is granted.'],
                    ['Automatic violation tracking','Absences automatically trigger the correct disciplinary action per DOLE guidelines.'],
                    ['Real-time notifications','Instant alerts for approvals, violations, and announcements delivered to the right person.'],
                    ['Audit-ready records','Employee records are preserved with status changes only — no data deletion for compliance.'],
                ] as [$title, $desc])
                <div class="check-item">
                    <div class="check-mark">✓</div>
                    <div>
                        <div class="check-item-title">{{ $title }}</div>
                        <div class="check-item-desc">{{ $desc }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="l-cta">
    <h2>Ready to modernize your HR?</h2>
    <p>Join hundreds of organizations managing their people with HRMS.</p>
    <div class="l-cta-btns">
        <a href="#" data-auth="register" class="btn btn-white btn-lg">Create Free Account</a>
        <a href="#" data-auth="login"    class="btn btn-outline-w btn-lg">Sign In</a>
    </div>
</section>

<footer class="l-footer">
    <p>&copy; {{ date('Y') }} HRMS — Human Resources Management System. All rights reserved.</p>
</footer>

{{-- ── AUTH MODAL ── --}}
<div class="modal-overlay" id="authModal">
    <div class="modal-box" onclick="event.stopPropagation()">
        <button class="modal-close" id="authClose">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <div class="modal-brand">
            <div class="modal-brand-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="modal-brand-name">HRMS</div>
                <div class="modal-brand-sub">Human Resources Management</div>
            </div>
        </div>

        <div class="modal-tabs">
            <button class="modal-tab active" id="tabLogin">Sign In</button>
            <button class="modal-tab" id="tabRegister">Create Account</button>
        </div>

        {{-- LOGIN PANEL --}}
        <div class="modal-panel active" id="panelLogin">
            <div class="modal-title">Welcome back</div>
            <div class="modal-sub">Sign in to your HRMS account to continue.</div>

            @if(session('pending_msg'))
            <div class="alert alert-warning">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
                {{ session('pending_msg') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                {{ session('error') }}
            </div>
            @endif
            @if($errors->has('email'))
            <div class="alert alert-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                {{ $errors->first('email') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="form-grid">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'err' : '' }}" value="{{ old('email') }}" placeholder="you@company.com" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                </div>
                <div class="remember-row">
                    <input type="checkbox" name="remember" id="rememberMe">
                    <label for="rememberMe">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-full" style="padding:11px 16px">Sign In</button>
            </form>
            <p class="modal-footer-text">Don't have an account? <a href="#" data-switch="register">Create one</a></p>
        </div>

        {{-- REGISTER PANEL --}}
        <div class="modal-panel" id="panelRegister">
            <div class="modal-title">Create account</div>
            <div class="modal-sub">After registering, your account will be reviewed by an administrator before you can log in.</div>

            @if(session('pending_msg') && ($errors->has('name') || old('name')))
            <div class="alert alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('pending_msg') }}
            </div>
            @endif
            @if($errors->any() && ($errors->has('name') || $errors->has('password')))
            <div class="alert alert-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="form-grid">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full name</label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'err' : '' }}" value="{{ old('name') }}" placeholder="Juan dela Cruz" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'err' : '' }}" value="{{ old('email') }}" placeholder="you@company.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'err' : '' }}" placeholder="At least 8 characters" required autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                </div>
                <button type="submit" class="btn btn-primary w-full" style="padding:11px 16px">Create Account</button>
            </form>
            <p class="admin-note">Admin approval required before first login.</p>
            <p class="modal-footer-text">Already have an account? <a href="#" data-switch="login">Sign in</a></p>
        </div>
    </div>
</div>

<script>
/* ── MODAL ── */
const modal      = document.getElementById('authModal');
const tabLogin   = document.getElementById('tabLogin');
const tabRegister= document.getElementById('tabRegister');
const panelLogin = document.getElementById('panelLogin');
const panelReg   = document.getElementById('panelRegister');

function openModal(tab) {
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    switchTab(tab || 'login');
}
function closeModal() {
    modal.classList.remove('open');
    document.body.style.overflow = '';
}
function switchTab(t) {
    const isLogin = t === 'login';
    tabLogin.classList.toggle('active', isLogin);
    tabRegister.classList.toggle('active', !isLogin);
    panelLogin.classList.toggle('active', isLogin);
    panelReg.classList.toggle('active', !isLogin);
}

document.getElementById('authClose').addEventListener('click', closeModal);
modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

tabLogin.addEventListener('click',    () => switchTab('login'));
tabRegister.addEventListener('click', () => switchTab('register'));

document.querySelectorAll('[data-auth]').forEach(el => {
    el.addEventListener('click', e => { e.preventDefault(); openModal(el.dataset.auth); });
});
document.querySelectorAll('[data-switch]').forEach(el => {
    el.addEventListener('click', e => { e.preventDefault(); switchTab(el.dataset.switch); });
});

/* Auto-open modal on errors */
@if(session('pending_msg') || session('error') || $errors->any())
document.addEventListener('DOMContentLoaded', () => {
    @if($errors->has('name') || $errors->has('password'))
    openModal('register');
    @else
    openModal('login');
    @endif
});
@endif

/* ── HERO CLOCK ── */
const timeEl = document.getElementById('heroTime');
function tick() {
    if (!timeEl) return;
    const n = new Date();
    timeEl.textContent =
        String(n.getHours()).padStart(2,'0') + ':' +
        String(n.getMinutes()).padStart(2,'0') + ':' +
        String(n.getSeconds()).padStart(2,'0');
}
tick(); setInterval(tick, 1000);

/* ── HERO BARS ── */
const barData  = [82, 91, 78, 95, 88, 0, 0];
const barColor = (v, i) => v === 0
    ? 'rgba(255,255,255,0.06)'
    : i === 3 ? '#60A5FA' : '#4ADE80';
const barsEl = document.getElementById('heroBars');
if (barsEl) {
    barData.forEach((v, i) => {
        const b = document.createElement('div');
        b.className = 'hv-bar-b';
        const h = v ? Math.max(8, Math.round(v * 0.52)) : 8;
        b.style.cssText = `height:${h}px;background:${barColor(v,i)};flex:1;border-radius:3px`;
        barsEl.appendChild(b);
    });
}

/* ── STAT COUNTER ── */
let counted = false;
function animateStats() {
    if (counted) return;
    counted = true;
    document.querySelectorAll('.stats-num').forEach(el => {
        const target = parseInt(el.dataset.target);
        const suffix = el.dataset.suffix || '';
        let cur = 0; const dur = 1300; const step = 16;
        const inc = target / (dur / step);
        const t = setInterval(() => {
            cur = Math.min(cur + inc, target);
            el.textContent = Math.round(cur).toLocaleString() + suffix;
            if (cur >= target) clearInterval(t);
        }, step);
    });
}
const statsSection = document.getElementById('stats');
if (statsSection) {
    const obs = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) animateStats();
    }, { threshold: 0.4 });
    obs.observe(statsSection);
}

/* ── NAVBAR SCROLL SHADOW ── */
window.addEventListener('scroll', () => {
    const nav = document.getElementById('landingNav');
    if (nav) nav.style.boxShadow = window.scrollY > 10 ? '0 1px 16px rgba(15,30,56,0.08)' : '';
}, { passive: true });
</script>

</body>
</html>
