<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'مركز مطمئنة الاستشاري' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:    #8b1c2b;
            --primary-light: #b02535;
            --primary-glow: rgba(139,28,43,0.15);
            --navy:       #1a1a2e;
            --gold:       #c8941a;
            --bg:         #f4f6f9;
            --bg-card:    #ffffff;
            --text:       #1e2a38;
            --text-dim:   #546e7a;
            --text-muted: #90a4ae;
            --border:     #dde3ea;
            --border-strong: #c5cdd8;
            --success:    #2e7d32;
            --danger:     #c62828;
            --shadow-sm:  0 1px 4px rgba(0,0,0,0.08);
            --shadow:     0 4px 16px rgba(0,0,0,0.1);
            --shadow-lg:  0 8px 32px rgba(0,0,0,0.12);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn    { from { opacity:0; } to { opacity:1; } }
        @keyframes dropIn    { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
        @keyframes pulse     { 0%,100% { opacity:1; } 50% { opacity:0.4; } }

        /* ═══ TOP BAR ═══ */
        .topbar {
            background: var(--navy);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 58px;
            position: sticky;
            top: 0;
            z-index: 200;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            animation: slideDown 0.3s ease;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .topbar-brand img {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid rgba(200,148,26,0.4);
        }

        .topbar-brand .t-name {
            font-size: 1.2rem;
            font-weight: 900;
            color: #ffffff;
            line-height: 1.1;
        }

        .topbar-brand .t-gold {
            font-size: 0.65rem;
            color: var(--gold);
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Nav */
        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 0;
            height: 100%;
            list-style: none;
        }

        .topbar-nav > li {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .topbar-nav > li > a {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0 1rem;
            height: 100%;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.88rem;
            transition: all 0.25s;
            position: relative;
            white-space: nowrap;
            border-bottom: 3px solid transparent;
        }

        .topbar-nav > li > a:hover,
        .topbar-nav > li.active > a {
            color: #fff;
            border-bottom-color: var(--gold);
            background: rgba(255,255,255,0.05);
        }

        .topbar-nav > li > a .nav-icon { font-size: 0.95rem; }

        /* Dropdown */
        .dd-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 240px;
            background: #fff;
            border: 1px solid var(--border);
            border-top: 3px solid var(--primary);
            border-radius: 0 0 12px 12px;
            box-shadow: var(--shadow-lg);
            list-style: none;
            overflow: hidden;
            z-index: 300;
        }

        .topbar-nav > li:hover .dd-menu {
            display: block;
            animation: dropIn 0.2s ease;
        }

        .dd-menu li a {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 1.2rem;
            color: var(--text-dim);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s;
        }

        .dd-menu li a:hover {
            background: #fef5f5;
            color: var(--primary);
            padding-right: 1.6rem;
        }

        .dd-menu li:last-child a { border-bottom: none; }

        /* Right */
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-date {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 50px;
            padding: 0.25rem 0.9rem;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.7);
            font-weight: 600;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: default;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 900;
            font-size: 0.85rem;
            position: relative;
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-dd-menu {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            min-width: 200px;
            background: #fff;
            border: 1px solid var(--border);
            border-top: 3px solid var(--primary);
            border-radius: 0 0 12px 12px;
            box-shadow: var(--shadow-lg);
            z-index: 400;
            overflow: hidden;
            animation: dropIn 0.2s ease;
        }

        .user-dd-menu.open { display: block; }

        .user-dd-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 1.2rem;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.2s;
            border-bottom: 1px solid #f0f0f0;
        }

        .user-dd-item:hover {
            background: #fef2f2;
            padding-right: 1.6rem;
        }

        .user-dd-item:last-child { border-bottom: none; }

        .online-dot {
            width: 8px;
            height: 8px;
            background: #4caf50;
            border-radius: 50%;
            border: 2px solid var(--navy);
            position: absolute;
            bottom: 0;
            left: 0;
            animation: pulse 2s infinite;
        }

        .user-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: rgba(255,255,255,0.85);
        }

        .mobile-toggle {
            display: none;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }

        /* ═══ CONTENT ═══ */
        .page-content {
            flex: 1;
            padding: 1.5rem 2rem;
            animation: fadeIn 0.4s ease;
        }

        /* ═══ FOOTER ═══ */
        .page-footer {
            background: var(--navy);
            padding: 0.75rem 2rem;
            text-align: center;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.45);
        }

        .page-footer strong { color: rgba(255,255,255,0.7); }

        /* ═══ UTILITY ═══ */
        .card {
            background: var(--bg-card);
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: box-shadow 0.25s;
        }

        .card:hover { box-shadow: var(--shadow); }

        .card-header {
            padding: 0.9rem 1.4rem;
            border-bottom: 1px solid var(--border);
            background: #fafbfc;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title { font-size: 0.95rem; font-weight: 800; color: var(--text); }
        .card-body  { padding: 1.4rem; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1.3rem;
            border-radius: 8px;
            font-family: 'Tajawal', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.25s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 3px 10px var(--primary-glow);
        }
        .btn-primary:hover { background: var(--primary-light); transform: translateY(-1px); box-shadow: 0 5px 15px var(--primary-glow); }

        .btn-secondary {
            background: #f0f2f5;
            color: var(--text-dim);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { background: #e8eaed; }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.65rem;
            border-radius: 6px;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .badge-green  { background: #e8f5e9; color: var(--success); }
        .badge-red    { background: #ffebee; color: var(--danger); }
        .badge-blue   { background: #e3f2fd; color: #1565c0; }
        .badge-amber  { background: #fff8e1; color: #e65100; }
        .badge-gray   { background: #f5f5f5; color: var(--text-dim); }

        .form-input {
            width: 100%;
            padding: 0.65rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: 'Tajawal', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s;
            background: #fff;
            color: var(--text);
        }
        .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-glow); }
        .form-input::placeholder { color: var(--text-muted); }


        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f0f0f0; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #aaa; }

        /* ═══ PAGINATION ═══ */
        .mtm-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            font-family: 'Tajawal', sans-serif;
        }

        .mtm-pg-info {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .mtm-pg-info strong {
            color: var(--text-dim);
            font-weight: 800;
        }

        .mtm-pg-nav {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            flex-wrap: wrap;
        }

        .mtm-pg-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 0.6rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            border: 1.5px solid var(--border);
            background: #fff;
            color: var(--text-dim);
            transition: all 0.18s;
            cursor: pointer;
        }

        a.mtm-pg-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-glow);
        }

        .mtm-pg-active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff !important;
            cursor: default;
            box-shadow: 0 2px 8px var(--primary-glow);
        }

        .mtm-pg-disabled {
            opacity: 0.35;
            cursor: not-allowed;
            background: #f8f9fa;
        }

        .mtm-pg-dots {
            border: none;
            background: transparent;
            color: var(--text-muted);
            cursor: default;
            min-width: 20px;
        }

        /* ═══ BOTTOM NAV (موبايل) ═══ */
        .btm-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: 60px;
            background: var(--navy);
            border-top: 2px solid rgba(200,148,26,0.35);
            z-index: 500;
            box-shadow: 0 -3px 16px rgba(0,0,0,0.25);
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        .btm-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 0.58rem;
            font-weight: 700;
            gap: 0.12rem;
            border: none;
            background: none;
            cursor: pointer;
            font-family: 'Tajawal', sans-serif;
            -webkit-tap-highlight-color: transparent;
            transition: color 0.15s;
        }
        .btm-nav-item .bi { font-size: 1.3rem; line-height: 1; }
        .btm-nav-item.active { color: var(--gold); }
        .btm-nav-item:active  { color: var(--gold); }

        /* ═══ MORE PANEL ═══ */
        .more-panel {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 800;
        }
        .more-panel.open { display: block; }

        .more-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
        }

        .more-sheet {
            position: absolute;
            bottom: 60px; left: 0; right: 0;
            background: var(--navy);
            border-radius: 18px 18px 0 0;
            border-top: 2px solid rgba(200,148,26,0.35);
            padding: 1rem 1rem 1.25rem;
            animation: slideUp 0.22s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); }
            to   { transform: translateY(0); }
        }

        .more-sheet-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.85rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .more-sheet-title {
            color: var(--gold);
            font-size: 0.88rem;
            font-weight: 800;
        }

        .more-sheet-close {
            background: rgba(255,255,255,0.1);
            border: none;
            color: rgba(255,255,255,0.7);
            width: 28px; height: 28px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex; align-items: center; justify-content: center;
        }

        .more-links {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }

        .more-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 0.9rem;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            background: rgba(255,255,255,0.06);
            border-radius: 10px;
            -webkit-tap-highlight-color: transparent;
        }
        .more-link:active { background: rgba(200,148,26,0.2); color: var(--gold); }

        /* ═══ RESPONSIVE ═══ */
        @media (max-width: 1024px) {
            .topbar-nav  { display: none !important; }
            .mobile-toggle { display: none !important; }
            .btm-nav { display: flex; }
            .page-content { padding-bottom: 72px; }
            .page-footer  { padding-bottom: 8px; margin-bottom: 60px; }
        }

        @media (max-width: 768px) {
            .page-content { padding: 0.85rem; }
            .topbar { padding: 0 0.85rem; }
            .user-name { display: none; }
            .topbar-date { display: none; }

            /* جداول: scroll أفقي */
            .card { overflow-x: auto; }
            table { min-width: 600px; }

            /* ─── Dashboard ─── */
            .dash-stats { grid-template-columns: repeat(2, 1fr) !important; }

            /* ─── Page Responsive Helpers ─── */
            .pg-outer   { padding: 0.6rem !important; }
            .pg-inner   { padding: 0.85rem !important; }

            /* شبكات 2 / 3 أعمدة → عمود واحد */
            .pg-2col    { grid-template-columns: 1fr !important; }
            .pg-2col > * { grid-column: span 1 !important; }
            .pg-3col    { grid-template-columns: 1fr !important; }
            .pg-autogrid { grid-template-columns: 1fr !important; max-width: 100% !important; }

            /* شريط الفلاتر flex → عمود */
            .pg-filter  { flex-direction: column !important; align-items: stretch !important; }
            .pg-filter > * { width: 100% !important; max-width: 100% !important; min-width: 0 !important; flex: none !important; }
            .pg-filter input, .pg-filter select { width: 100% !important; min-width: 0 !important; max-width: none !important; }

            /* حقل بحث بعرض ثابت */
            .pg-sw      { width: 100% !important; max-width: 100% !important; }
        }

        @media (max-width: 480px) {
            .page-content { padding: 0.6rem; }
            .topbar-brand .t-name { font-size: 1rem; }
        }
    </style>
</head>
<body>

    <!-- ═══════ TOP BAR ═══════ -->
    <header class="topbar">
        <a href="{{ route('dashboard') }}" wire:navigate class="topbar-brand">
            <img src="/logo.jpg" alt="مطمئنة">
            <div>
                <div class="t-name">مطمئنة</div>
                <div class="t-gold">المركز الاستشاري</div>
            </div>
        </a>

        <ul class="topbar-nav" id="topNav">
            <li class="{{ request()->routeIs('checks.*') ? 'active' : '' }}">
                <a href="{{ route('checks.index') }}">
                    <span class="nav-icon">📋</span> الكشوف
                </a>
            </li>

            <li class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <a href="{{ route('patients.index') }}">
                    <span class="nav-icon">👥</span> العملاء
                </a>
            </li>

            <li class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                <a href="{{ route('appointments.index') }}">
                    <span class="nav-icon">📅</span> المواعيد
                </a>
            </li>

            <li class="{{ request()->routeIs('finance.*') ? 'active' : '' }}">
                <a href="{{ route('finance.movements') }}">
                    <span class="nav-icon">💰</span> المالية
                </a>
                <ul class="dd-menu">
                    <li><a href="{{ route('finance.movements') }}"><span>💳</span> حركات مالية</a></li>
                    <li><a href="{{ route('finance.statement') }}"><span>📄</span> بيان حساب</a></li>
                </ul>
            </li>

            <li class="{{ request()->routeIs('finance.reports') ? 'active' : '' }}">
                <a href="{{ route('finance.reports') }}">
                    <span class="nav-icon">📊</span> التقارير
                </a>
                <ul class="dd-menu">
                    <li><a href="{{ route('finance.invoices') }}"><span>💳</span> الفواتير</a></li>
                    <li><a href="{{ route('finance.vouchers') }}"><span>📑</span> السندات</a></li>
                    <li><a href="{{ route('finance.branch-report') }}"><span>🏢</span> تقرير الفروع</a></li>
                    <li><a href="{{ route('finance.reports') }}?type=pb"><span>💰</span> أرصدة العملاء</a></li>
                    <li><a href="{{ route('finance.reports') }}?type=services"><span>🔬</span> الخدمات</a></li>
                    <li><a href="{{ route('finance.reports') }}?type=appointments"><span>📅</span> المواعيد</a></li>
                    <li><a href="{{ route('finance.reports') }}?type=clinics"><span>🏛️</span> العيادات</a></li>
                    <li><a href="{{ route('finance.reports') }}?type=pfs"><span>📊</span> البيان المالي</a></li>
                </ul>
            </li>

            @php $authId = auth()->user()?->getAuthIdentifier(); @endphp
            @if(in_array($authId, [107, 189]))
            <li class="{{ request()->routeIs('system.*') ? 'active' : '' }}">
                <a href="{{ route('system.settings') }}">
                    <span class="nav-icon">⚙️</span> الإعدادات
                </a>
                <ul class="dd-menu">
                    <li><a href="{{ route('clinics.index') }}"><span>🏥</span> العيادات</a></li>
                    <li><a href="{{ route('employees.index') }}"><span>👨‍⚕️</span> الموظفين</a></li>
                    <li><a href="{{ route('system.users') }}"><span>👤</span> المستخدمين</a></li>
                    <li><a href="{{ route('system.backup') }}"><span>💾</span> باك اب</a></li>
                </ul>
            </li>
            @endif
        </ul>

        <div class="topbar-right">
            <div class="topbar-date">📅 {{ now()->locale('ar')->isoFormat('D MMM YYYY') }}</div>

            <!-- User Dropdown -->
            <div class="user-dropdown" id="userDropdown">
                <div class="user-chip" id="userChipBtn" onclick="toggleUserMenu()" style="cursor:pointer;">
                    <div style="position: relative;">
                        <div class="user-avatar">
                            {{ mb_substr(auth()->user()?->getName() ?? 'م', 0, 1) }}
                            <span class="online-dot"></span>
                        </div>
                    </div>
                    <span class="user-name">{{ auth()->user()?->getName() ?? 'مدير النظام' }}</span>
                    <span style="color:rgba(255,255,255,0.4); font-size:0.65rem; margin-right:0.1rem;">▾</span>
                </div>

                <div class="user-dd-menu" id="userDdMenu">
                    <div style="padding:0.75rem 1rem; border-bottom:1px solid #f0f0f0;">
                        <div style="font-weight:800; color:var(--navy); font-size:0.88rem;">{{ auth()->user()?->getName() ?? 'مدير النظام' }}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.15rem;">مسؤول النظام</div>
                    </div>
                    <a href="{{ route('2fa.setup') }}" class="user-dd-item" style="display:block; padding:0.6rem 1rem; text-decoration:none; color:var(--navy); font-size:0.85rem; font-weight:700;">
                        <span>🔐</span> المصادقة الثنائية
                        @php $emp2fa = DB::table('employees')->where('id', auth()->id())->value('two_factor_enabled'); @endphp
                        @if($emp2fa)
                            <span style="background:#dcfce7; color:#16a34a; font-size:0.65rem; font-weight:800; padding:0.1rem 0.4rem; border-radius:4px; margin-right:4px;">مفعّل</span>
                        @else
                            <span style="background:#fef3c7; color:#d97706; font-size:0.65rem; font-weight:800; padding:0.1rem 0.4rem; border-radius:4px; margin-right:4px;">معطّل</span>
                        @endif
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="user-dd-item" style="width:100%; border:none; background:none; cursor:pointer; text-align:right; font-family:'Tajawal',sans-serif; color:#dc2626;">
                            <span>🚪</span> تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════ CONTENT ═══════ -->
    <main class="page-content">
        {{ $slot }}
    </main>

    <!-- ═══════ FOOTER ═══════ -->
    <footer class="page-footer">
        جميع الحقوق محفوظة &copy; {{ date('Y') }} &mdash; <strong>مركز مطمئنة الاستشاري</strong>
    </footer>

    <!-- ═══════ BOTTOM NAV (موبايل فقط) ═══════ -->
    @php $authId = auth()->user()?->getAuthIdentifier(); @endphp
    <nav class="btm-nav">
        <a href="{{ route('checks.index') }}" class="btm-nav-item {{ request()->routeIs('checks.*') ? 'active' : '' }}">
            <span class="bi">📋</span><span>الكشوف</span>
        </a>
        <a href="{{ route('patients.index') }}" class="btm-nav-item {{ request()->routeIs('patients.*') ? 'active' : '' }}">
            <span class="bi">👥</span><span>العملاء</span>
        </a>
        <a href="{{ route('appointments.index') }}" class="btm-nav-item {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
            <span class="bi">📅</span><span>المواعيد</span>
        </a>
        <a href="{{ route('finance.movements') }}" class="btm-nav-item {{ request()->routeIs('finance.*') ? 'active' : '' }}">
            <span class="bi">💰</span><span>المالية</span>
        </a>
        <button class="btm-nav-item {{ request()->routeIs('finance.reports','finance.invoices','finance.vouchers','system.*','clinics.*','employees.*') ? 'active' : '' }}" onclick="toggleMore()">
            <span class="bi">⋯</span><span>المزيد</span>
        </button>
    </nav>

    <!-- ═══════ MORE PANEL ═══════ -->
    <div class="more-panel" id="morePanel">
        <div class="more-backdrop" onclick="toggleMore()"></div>
        <div class="more-sheet">
            <div class="more-sheet-header">
                <span class="more-sheet-title">القائمة الكاملة</span>
                <button class="more-sheet-close" onclick="toggleMore()">✕</button>
            </div>
            <div class="more-links">
                <a href="{{ route('finance.reports') }}" class="more-link">📊 التقارير</a>
                <a href="{{ route('finance.invoices') }}" class="more-link">💳 الفواتير</a>
                <a href="{{ route('finance.vouchers') }}" class="more-link">📑 السندات</a>
                <a href="{{ route('finance.statement') }}" class="more-link">📄 بيان حساب</a>
                <a href="{{ route('finance.reports') }}?type=pb" class="more-link">💰 أرصدة العملاء</a>
                <a href="{{ route('finance.reports') }}?type=services" class="more-link">🔬 الخدمات</a>
                <a href="{{ route('finance.reports') }}?type=clinics" class="more-link">🏛 العيادات</a>
                <a href="{{ route('finance.reports') }}?type=pfs" class="more-link">📈 البيان المالي</a>
                @if(in_array($authId, [107, 189]))
                <a href="{{ route('clinics.index') }}" class="more-link">🏥 العيادات</a>
                <a href="{{ route('employees.index') }}" class="more-link">👨‍⚕️ الموظفين</a>
                <a href="{{ route('system.users') }}" class="more-link">👤 المستخدمين</a>
                <a href="{{ route('system.backup') }}" class="more-link">💾 باك اب</a>
                @endif
            </div>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')

    {{-- ═══ أنماط الطباعة المشتركة ═══ --}}
    <style>
    /* إخفاء عناصر الطباعة على الشاشة العادية */
    .print-only       { display: none !important; }
    .print-letterhead { display: none !important; }
    .print-footer     { display: none !important; }

    @media print {
        /* إخفاء كل شيء في الصفحة */
        body * { visibility: hidden !important; }

        /* إظهار منطقة الطباعة وكل محتواها */
        #print-area,
        #print-area * { visibility: visible !important; }

        /* ═══ المفتاح: absolute بدل fixed — يسمح بصفحات متعددة ═══ */
        #print-area {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0.75rem 1.25rem !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            background: #fff !important;
            overflow: visible !important;
        }

        /* ترويسة وتذييل الطباعة */
        #print-area .print-letterhead { display: flex  !important; }
        #print-area .print-footer     { display: block !important; }

        /* إخفاء أزرار وعناصر الشاشة */
        #print-area .no-print { display: none !important; visibility: hidden !important; }

        /* نسخة الطباعة للسجل الاستشاري */
        #print-area .print-only {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
        }

        /* الجداول: thead يتكرر بكل صفحة، tfoot لا يتكرر */
        table  { page-break-inside: auto !important; }
        tr     { page-break-inside: avoid !important; page-break-after: auto !important; }
        thead  { display: table-header-group !important; }
        tfoot  { display: table-row-group  !important; }

        /* ضمان ظهور الألوان */
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
    </style>

    <script>
        function toggleMore() {
            document.getElementById('morePanel').classList.toggle('open');
        }

        function toggleUserMenu() {
            document.getElementById('userDdMenu').classList.toggle('open');
        }

        document.addEventListener('click', function(e) {
            const dd = document.getElementById('userDropdown');
            if (dd && !dd.contains(e.target)) {
                document.getElementById('userDdMenu')?.classList.remove('open');
            }
        });
    </script>
</body>
</html>
