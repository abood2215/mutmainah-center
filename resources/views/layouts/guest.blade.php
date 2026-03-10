<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'تسجيل الدخول — مركز مطمئنة' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:   #8b1c2b;
            --primary-l: #a82535;
            --gold:      #c8941a;
            --gold-l:    #e8a820;
            --navy:      #1a1a2e;
            --text:      #2d2d3a;
            --text-muted:#6b7280;
            --border:    #e8ddd4;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            min-height: 100vh;
            overflow: hidden;
            background: #f2ebe0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ══════════ BACKGROUND ══════════ */
        .bg-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background: linear-gradient(145deg, #f7f0e6 0%, #ede3d5 45%, #f0e8db 100%);
        }

        /* بقع ضبابية كبيرة */
        .bg-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: floatBlob 14s ease-in-out infinite;
        }
        .bg-blob-1 {
            width: 650px; height: 650px;
            background: radial-gradient(circle, rgba(200,148,26,0.28) 0%, transparent 65%);
            top: -200px; right: -180px;
            animation-delay: 0s;
        }
        .bg-blob-2 {
            width: 520px; height: 520px;
            background: radial-gradient(circle, rgba(139,28,43,0.2) 0%, transparent 65%);
            bottom: -180px; left: -120px;
            animation-delay: -6s;
        }
        .bg-blob-3 {
            width: 360px; height: 360px;
            background: radial-gradient(circle, rgba(200,148,26,0.22) 0%, transparent 65%);
            top: 32%; left: 22%;
            animation-delay: -11s;
            animation-duration: 18s;
        }
        .bg-blob-4 {
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(139,28,43,0.15) 0%, transparent 65%);
            top: 6%; left: 48%;
            animation-delay: -3s;
            animation-duration: 20s;
        }

        /* شبكة خفيفة */
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(139,28,43,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139,28,43,0.04) 1px, transparent 1px);
            background-size: 58px 58px;
            animation: gridShift 30s linear infinite;
        }

        /* دوائر متحركة واضحة */
        .bg-ring {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(200,148,26,0.25);
            animation: expandRing 6s ease-out infinite;
        }
        .bg-ring-1 { width: 120px; height: 120px; top: 15%; left: 8%;  animation-delay: 0s; }
        .bg-ring-2 { width: 90px;  height: 90px;  top: 70%; left: 85%; animation-delay: -2s; }
        .bg-ring-3 { width: 60px;  height: 60px;  top: 45%; left: 92%; animation-delay: -4s; }
        .bg-ring-4 { width: 100px; height: 100px; top: 82%; left: 20%; animation-delay: -1s; }
        .bg-ring-5 { width: 70px;  height: 70px;  top: 5%;  left: 70%; animation-delay: -3s; }

        /* معينات دوارة */
        .bg-diamond {
            position: absolute;
            border: 1.5px solid rgba(139,28,43,0.18);
            animation: rotateDiamond 12s linear infinite;
        }
        .bg-diamond-1 {
            width: 55px; height: 55px;
            top: 20%; right: 12%;
            transform: rotate(45deg);
            animation-delay: 0s;
        }
        .bg-diamond-2 {
            width: 35px; height: 35px;
            top: 60%; left: 6%;
            transform: rotate(45deg);
            animation-delay: -4s;
        }
        .bg-diamond-3 {
            width: 45px; height: 45px;
            top: 78%; right: 25%;
            transform: rotate(45deg);
            animation-delay: -8s;
        }
        .bg-diamond-4 {
            width: 28px; height: 28px;
            top: 10%; left: 30%;
            transform: rotate(45deg);
            animation-delay: -2s;
            border-color: rgba(200,148,26,0.22);
        }

        /* خطوط مائلة متحركة */
        .bg-slash {
            position: absolute;
            width: 1px;
            background: linear-gradient(180deg, transparent, rgba(200,148,26,0.3), transparent);
            animation: slideSlash 8s ease-in-out infinite;
        }
        .bg-slash-1 { height: 180px; top: 5%;  left: 15%; transform: rotate(15deg); animation-delay: 0s; }
        .bg-slash-2 { height: 140px; top: 55%; right: 10%; transform: rotate(-20deg); animation-delay: -3s; }
        .bg-slash-3 { height: 100px; top: 30%; left: 80%; transform: rotate(10deg); animation-delay: -5s; }

        /* نقاط عائمة */
        .bg-dot {
            position: absolute;
            border-radius: 50%;
            background: var(--gold);
            animation: floatDot 7s ease-in-out infinite;
        }

        /* خطوط أفقية مضيئة */
        .bg-lines {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }
        .bg-line {
            position: absolute;
            background: linear-gradient(90deg, transparent, rgba(200,148,26,0.2), transparent);
            height: 1.5px;
            width: 100%;
            animation: slideLine 9s linear infinite;
        }
        .bg-line:nth-child(1) { top: 16%; animation-delay: 0s; }
        .bg-line:nth-child(2) { top: 38%; animation-delay: -3s; opacity: 0.6; }
        .bg-line:nth-child(3) { top: 63%; animation-delay: -6s; }
        .bg-line:nth-child(4) { top: 84%; animation-delay: -8.5s; opacity: 0.5; }

        /* نقاط لامعة */
        .star {
            position: absolute;
            background: var(--gold);
            border-radius: 50%;
            animation: twinkle 4s ease-in-out infinite;
        }

        /* ══════════ CARD ══════════ */
        .login-card {
            position: relative;
            z-index: 10;
            width: 440px;
            max-width: calc(100vw - 2rem);
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem 2.75rem 2rem;
            box-shadow:
                0 2px 0 rgba(200,148,26,0.25),
                0 20px 60px rgba(0,0,0,0.10),
                0 4px 16px rgba(139,28,43,0.06);
            animation: cardIn 0.75s cubic-bezier(0.16,1,0.3,1) forwards;
            opacity: 0;
            transform: translateY(28px) scale(0.97);
        }

        /* شريط ذهبي علوي */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 20%; right: 20%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            border-radius: 0 0 4px 4px;
        }

        /* ══════════ LOGO ══════════ */
        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.75rem;
            animation: fadeDown 0.7s ease 0.25s both;
        }

        .logo-ring {
            position: relative;
            width: 84px;
            height: 84px;
            margin-bottom: 1.1rem;
        }

        .logo-ring-svg {
            position: absolute;
            inset: 0;
            animation: spinRing 10s linear infinite;
        }

        .logo-img-wrap {
            position: absolute;
            inset: 11px;
            border-radius: 50%;
            overflow: hidden;
            background: #f8f3ec;
            border: 2px solid rgba(200,148,26,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-fallback {
            font-size: 2rem;
            display: none;
        }

        .logo-name {
            font-size: 1.35rem;
            font-weight: 900;
            color: var(--navy);
            letter-spacing: -0.3px;
            line-height: 1.2;
            text-align: center;
        }

        .logo-name span {
            color: var(--primary);
        }

        /* ══════════ DIVIDER ══════════ */
        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.6rem;
            animation: fadeDown 0.7s ease 0.4s both;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider-text {
            font-size: 0.74rem;
            color: var(--text-muted);
            font-weight: 700;
            letter-spacing: 1.5px;
            white-space: nowrap;
        }

        /* ══════════ ERROR BOX ══════════ */
        .error-box {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            margin-bottom: 1.2rem;
            color: #b91c1c;
            font-size: 0.87rem;
            font-weight: 700;
            animation: shake 0.45s cubic-bezier(0.36,0.07,0.19,0.97) both;
        }

        .error-icon {
            width: 22px; height: 22px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
            color: #dc2626;
        }

        /* ══════════ FORM ══════════ */
        .form-group {
            margin-bottom: 1.15rem;
            animation: fadeUp 0.55s ease both;
        }
        .form-group:nth-child(1) { animation-delay: 0.45s; }
        .form-group:nth-child(2) { animation-delay: 0.55s; }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            opacity: 0.35;
            pointer-events: none;
            transition: opacity 0.25s;
        }

        .form-input {
            width: 100%;
            background: #fafaf9;
            border: 1.5px solid #ddd5c8;
            border-radius: 10px;
            padding: 0.82rem 2.8rem 0.82rem 1rem;
            font-family: 'Tajawal', sans-serif;
            font-size: 0.95rem;
            color: var(--text);
            outline: none;
            transition: all 0.25s;
        }

        .form-input::placeholder {
            color: #b8a898;
        }

        .form-input:focus {
            border-color: var(--gold);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(200,148,26,0.1);
        }

        .form-input:focus + .input-icon {
            opacity: 0.65;
        }

        /* ══════════ BUTTON ══════════ */
        .btn-login-wrap {
            margin-top: 1.6rem;
            animation: fadeUp 0.55s ease 0.65s both;
        }

        .btn-login {
            width: 100%;
            position: relative;
            padding: 0.9rem;
            border: none;
            border-radius: 10px;
            font-family: 'Tajawal', sans-serif;
            font-size: 1rem;
            font-weight: 900;
            color: #fff;
            cursor: pointer;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-l) 100%);
            transition: transform 0.2s, box-shadow 0.25s, opacity 0.2s;
            box-shadow: 0 4px 18px rgba(139,28,43,0.35);
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 48px;
        }

        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(139,28,43,0.45);
        }

        .btn-login:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-shimmer {
            position: absolute;
            top: 0; left: -100%;
            width: 55%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
            animation: shimmer 3.5s ease-in-out infinite;
        }

        /* spinner */
        .spin-icon {
            animation: spinRing 0.9s linear infinite;
        }

        /* ══════════ FOOTER ══════════ */
        .card-footer {
            margin-top: 1.6rem;
            text-align: center;
            animation: fadeUp 0.55s ease 0.75s both;
        }

        .card-footer p {
            font-size: 0.74rem;
            color: #b8a898;
            font-weight: 600;
        }

        .gold-dot {
            display: inline-block;
            width: 4px; height: 4px;
            background: var(--gold);
            border-radius: 50%;
            margin: 0 0.4rem;
            vertical-align: middle;
            opacity: 0.8;
        }

        /* ══════════ ANIMATIONS ══════════ */
        @keyframes floatBlob {
            0%, 100% { transform: translate(0,0) scale(1); }
            33%       { transform: translate(32px,-26px) scale(1.07); }
            66%       { transform: translate(-22px,20px) scale(0.95); }
        }
        @keyframes slideLine {
            0%   { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50%       { opacity: 0.7; transform: scale(1.6); }
        }
        @keyframes expandRing {
            0%   { transform: scale(0.6); opacity: 0.8; }
            100% { transform: scale(2.2); opacity: 0; }
        }
        @keyframes rotateDiamond {
            0%   { transform: rotate(45deg) scale(1); }
            50%  { transform: rotate(225deg) scale(1.15); }
            100% { transform: rotate(405deg) scale(1); }
        }
        @keyframes slideSlash {
            0%, 100% { opacity: 0.15; transform: translateY(0) rotate(15deg); }
            50%       { opacity: 0.55; transform: translateY(-18px) rotate(15deg); }
        }
        @keyframes floatDot {
            0%, 100% { transform: translateY(0); opacity: 0.4; }
            50%       { transform: translateY(-20px); opacity: 0.9; }
        }
        @keyframes gridShift {
            0%   { background-position: 0 0; }
            100% { background-position: 58px 58px; }
        }
        @keyframes spinRing {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes cardIn {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes shake {
            10%, 90%      { transform: translateX(-2px); }
            20%, 80%      { transform: translateX(4px); }
            30%, 50%, 70% { transform: translateX(-5px); }
            40%, 60%      { transform: translateX(5px); }
        }
        @keyframes shimmer {
            0%   { left: -100%; }
            55%  { left: 120%; }
            100% { left: 120%; }
        }
        @keyframes spinDash {
            to { stroke-dashoffset: -280; }
        }
    </style>
</head>
<body>

    <!-- ═══════ BACKGROUND ═══════ -->
    <div class="bg-canvas">
        <div class="bg-grid"></div>

        <!-- بقع ضبابية -->
        <div class="bg-blob bg-blob-1"></div>
        <div class="bg-blob bg-blob-2"></div>
        <div class="bg-blob bg-blob-3"></div>
        <div class="bg-blob bg-blob-4"></div>

        <!-- خطوط أفقية -->
        <div class="bg-lines">
            <div class="bg-line"></div>
            <div class="bg-line"></div>
            <div class="bg-line"></div>
            <div class="bg-line"></div>
        </div>

        <!-- دوائر موجات -->
        <div class="bg-ring bg-ring-1"></div>
        <div class="bg-ring bg-ring-2"></div>
        <div class="bg-ring bg-ring-3"></div>
        <div class="bg-ring bg-ring-4"></div>
        <div class="bg-ring bg-ring-5"></div>

        <!-- معينات دوارة -->
        <div class="bg-diamond bg-diamond-1"></div>
        <div class="bg-diamond bg-diamond-2"></div>
        <div class="bg-diamond bg-diamond-3"></div>
        <div class="bg-diamond bg-diamond-4"></div>

        <!-- خطوط مائلة -->
        <div class="bg-slash bg-slash-1"></div>
        <div class="bg-slash bg-slash-2" style="transform:rotate(-20deg);"></div>
        <div class="bg-slash bg-slash-3" style="transform:rotate(10deg);"></div>

        <!-- نقاط عائمة -->
        <div class="bg-dot" style="width:6px;height:6px;top:18%;left:22%;animation-delay:0s;opacity:0.4;"></div>
        <div class="bg-dot" style="width:5px;height:5px;top:35%;left:88%;animation-delay:-2s;opacity:0.35;"></div>
        <div class="bg-dot" style="width:7px;height:7px;top:65%;left:15%;animation-delay:-4s;opacity:0.45;"></div>
        <div class="bg-dot" style="width:4px;height:4px;top:80%;left:70%;animation-delay:-1s;opacity:0.3;"></div>
        <div class="bg-dot" style="width:6px;height:6px;top:10%;left:55%;animation-delay:-3s;opacity:0.4;"></div>
        <div class="bg-dot" style="width:5px;height:5px;top:50%;left:40%;animation-delay:-5s;opacity:0.35;"></div>

        <!-- نقاط لامعة صغيرة -->
        <div class="star" style="width:3px;height:3px;top:11%;left:18%;animation-delay:0s;"></div>
        <div class="star" style="width:3px;height:3px;top:24%;left:78%;animation-delay:-1.8s;"></div>
        <div class="star" style="width:4px;height:4px;top:57%;left:9%;animation-delay:-3.2s;"></div>
        <div class="star" style="width:3px;height:3px;top:72%;left:87%;animation-delay:-0.6s;"></div>
        <div class="star" style="width:4px;height:4px;top:86%;left:44%;animation-delay:-2.4s;"></div>
        <div class="star" style="width:3px;height:3px;top:42%;left:62%;animation-delay:-4.1s;"></div>
        <div class="star" style="width:4px;height:4px;top:6%;left:54%;animation-delay:-3.7s;"></div>
        <div class="star" style="width:3px;height:3px;top:93%;left:33%;animation-delay:-1.2s;"></div>
    </div>

    <!-- ═══════ CARD ═══════ -->
    <div class="login-card">

        <!-- الشعار -->
        <div class="logo-wrap">
            <div class="logo-ring">
                <svg class="logo-ring-svg" viewBox="0 0 84 84" fill="none">
                    <circle cx="42" cy="42" r="39" stroke="url(#gGrad)" stroke-width="1.5"
                        stroke-dasharray="10 7" stroke-linecap="round"
                        style="animation:spinDash 5s linear infinite; animation-direction:reverse;"/>
                    <defs>
                        <linearGradient id="gGrad" x1="0" y1="0" x2="84" y2="84" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#c8941a"/>
                            <stop offset="55%" stop-color="#e8a820"/>
                            <stop offset="100%" stop-color="#c8941a" stop-opacity="0.2"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="logo-img-wrap">
                    <img src="/logo.jpg" alt="مطمئنة" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-fallback">🧠</div>
                </div>
            </div>
            <div class="logo-name">مركز مطمئنة <span>للاستشارات النفسية</span></div>
        </div>

        <!-- فاصل -->
        <div class="divider">
            <div class="divider-line"></div>
            <div class="divider-text">تسجيل الدخول</div>
            <div class="divider-line"></div>
        </div>

        {{ $slot }}

        <!-- ذيل الكارد -->
        <div class="card-footer">
            <p>
                مركز مطمئنة للاستشارات النفسية
                <span class="gold-dot"></span>
                جميع الحقوق محفوظة &copy; {{ date('Y') }}
            </p>
        </div>
    </div>

    @livewireScripts
</body>
</html>
