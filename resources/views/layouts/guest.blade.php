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
            --primary-l: #b02535;
            --gold:      #c8941a;
            --gold-l:    #e8ab20;
        }

        html, body {
            height: 100%;
            font-family: 'Tajawal', sans-serif;
            overflow: hidden;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ══════ BACKGROUND IMAGE ══════ */
        .bg-photo {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: url('/center-bg.webp') center center / cover no-repeat;
            animation: slowZoom 20s ease-in-out infinite alternate;
        }

        /* طبقة blur + تعتيم */
        .bg-blur {
            position: fixed;
            inset: 0;
            z-index: 1;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(10, 5, 5, 0.45);
        }

        /* تدرج لوني فوقها لإضفاء الهوية */
        .bg-tint {
            position: fixed;
            inset: 0;
            z-index: 2;
            background:
                radial-gradient(ellipse 90% 70% at 10% 10%,  rgba(139,28,43,0.30) 0%, transparent 60%),
                radial-gradient(ellipse 80% 60% at 90% 90%,  rgba(200,148,26,0.20) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 50% 50%,  rgba(0,0,0,0.10)     0%, transparent 70%);
        }

        /* ══════ CARD WRAP ══════ */
        .login-wrap {
            position: relative;
            z-index: 10;
            width: 430px;
            max-width: calc(100vw - 2rem);
            animation: cardIn 0.9s cubic-bezier(0.16,1,0.3,1) forwards;
            opacity: 0;
            transform: translateY(36px) scale(0.96);
        }

        /* توهج خارجي ينبض */
        .card-glow {
            position: absolute;
            inset: -24px;
            border-radius: 36px;
            background: radial-gradient(ellipse at 50% 50%,
                rgba(139,28,43,0.35) 0%,
                rgba(200,148,26,0.12) 45%,
                transparent 70%);
            filter: blur(18px);
            animation: glowPulse 4s ease-in-out infinite;
            z-index: -1;
        }

        /* الكارد الزجاجي */
        .login-card {
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border-radius: 24px;
            border: 1px solid rgba(255,255,255,0.18);
            padding: 2.75rem 3rem 2.25rem;
            box-shadow:
                0 0 0 1px rgba(200,148,26,0.15),
                0 32px 80px rgba(0,0,0,0.45),
                inset 0 1px 0 rgba(255,255,255,0.25),
                inset 0 -1px 0 rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        /* شريط ذهبي علوي */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 12%; right: 12%;
            height: 2px;
            background: linear-gradient(90deg,
                transparent,
                rgba(200,148,26,0.6),
                rgba(255,230,120,0.9),
                rgba(200,148,26,0.6),
                transparent);
            border-radius: 99px;
        }

        /* بريق داخلي يمر */
        .card-shine {
            position: absolute;
            top: 0; left: -80%;
            width: 60%; height: 100%;
            background: linear-gradient(105deg,
                transparent 30%,
                rgba(255,255,255,0.07) 50%,
                transparent 70%);
            animation: shinePass 7s ease-in-out infinite;
            pointer-events: none;
        }

        /* ══════ LOGO ══════ */
        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.75rem;
            animation: fadeDown 0.7s ease 0.3s both;
        }

        .logo-ring {
            position: relative;
            width: 130px; height: 130px;
            margin-bottom: 1.1rem;
        }

        /* حلقة دوارة خارجية */
        .ring-spin {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 1.5px solid transparent;
            background:
                linear-gradient(rgba(255,255,255,0) 0%, rgba(255,255,255,0) 100%) padding-box,
                conic-gradient(from 0deg, var(--gold), transparent 60%, var(--gold)) border-box;
            animation: spinRing 5s linear infinite;
        }

        /* حلقة ثابتة */
        .ring-static {
            position: absolute;
            inset: 5px;
            border-radius: 50%;
            border: 1px dashed rgba(255,255,255,0.2);
        }

        /* مركز الشعار */
        .logo-center {
            position: absolute;
            inset: 12px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.25);
            box-shadow: 0 4px 16px rgba(0,0,0,0.4);
        }

        .logo-center img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .logo-fallback {
            width: 100%; height: 100%;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(139,28,43,0.8);
            font-size: 1.8rem;
        }

        .logo-title {
            font-size: 1.2rem;
            font-weight: 900;
            color: #fff;
            text-align: center;
            line-height: 1.5;
            text-shadow: 0 2px 8px rgba(0,0,0,0.4);
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.22);
            border-radius: 14px;
            padding: 0.55rem 1.4rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        .logo-title span {
            color: #000;
            -webkit-text-fill-color: #000;
        }

        /* ══════ DIVIDER ══════ */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.6rem;
            animation: fadeIn 0.6s ease 0.5s both;
        }
        .div-line {
            flex: 1; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        }
        .div-text {
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(255,255,255,0.45);
            letter-spacing: 2px;
            white-space: nowrap;
        }

        /* ══════ ERROR ══════ */
        .error-box {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            background: rgba(220,38,38,0.12);
            border: 1px solid rgba(220,38,38,0.3);
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-bottom: 1.25rem;
            color: #fca5a5;
            font-size: 0.87rem;
            font-weight: 700;
            animation: shake 0.45s ease both;
        }
        .error-icon {
            width: 22px; height: 22px;
            background: rgba(220,38,38,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; flex-shrink: 0;
        }

        /* ══════ FORM ══════ */
        .form-group {
            margin-bottom: 1.15rem;
            animation: fadeUp 0.55s ease both;
        }
        .form-group:nth-child(1) { animation-delay: 0.55s; }
        .form-group:nth-child(2) { animation-delay: 0.65s; }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 6px rgba(0,0,0,0.6);
        }

        .input-wrap { position: relative; }

        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.22);
            border: 1.5px solid rgba(255,255,255,0.40);
            border-radius: 12px;
            padding: 0.85rem 2.8rem 0.85rem 1rem;
            font-family: 'Tajawal', sans-serif;
            font-size: 0.97rem;
            font-weight: 700;
            color: #fff;
            outline: none;
            transition: all 0.3s;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
        .form-input::placeholder { color: rgba(255,255,255,0.55); }
        .form-input:focus {
            border-color: var(--gold-l);
            background: rgba(255,255,255,0.28);
            box-shadow: 0 0 0 3px rgba(200,148,26,0.25), 0 0 20px rgba(200,148,26,0.1);
        }

        .input-icon {
            position: absolute;
            right: 0.9rem; top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            opacity: 0.7;
            pointer-events: none;
            transition: opacity 0.25s;
        }
        .form-input:focus + .input-icon { opacity: 1; }

        /* ══════ BUTTON ══════ */
        .btn-login-wrap {
            margin-top: 1.75rem;
            animation: fadeUp 0.55s ease 0.75s both;
        }

        .btn-login {
            width: 100%;
            position: relative;
            padding: 0.95rem;
            border: none;
            border-radius: 12px;
            font-family: 'Tajawal', sans-serif;
            font-size: 1rem;
            font-weight: 900;
            color: #fff;
            cursor: pointer;
            overflow: hidden;
            background: linear-gradient(135deg, #6e1520 0%, var(--primary) 45%, var(--primary-l) 100%);
            transition: transform 0.2s, box-shadow 0.25s, opacity 0.2s;
            box-shadow: 0 4px 24px rgba(139,28,43,0.55), 0 0 0 1px rgba(200,148,26,0.18);
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 50px;
        }
        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(139,28,43,0.65), 0 0 0 1px rgba(200,148,26,0.28);
        }
        .btn-login:active:not(:disabled) { transform: translateY(0); }
        .btn-login:disabled { opacity: 0.65; cursor: not-allowed; }

        .btn-shimmer {
            position: absolute;
            top: 0; left: -100%;
            width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }
        .spin-icon { animation: spinRing 0.85s linear infinite; }

        /* ══════ FOOTER ══════ */
        .card-footer {
            margin-top: 1.6rem;
            text-align: center;
            animation: fadeIn 0.6s ease 0.85s both;
        }
        .card-footer p {
            font-size: 0.73rem;
            color: rgba(255,255,255,0.3);
            font-weight: 600;
        }
        .gold-dot {
            display: inline-block;
            width: 4px; height: 4px;
            background: var(--gold);
            border-radius: 50%;
            margin: 0 0.4rem;
            vertical-align: middle;
            opacity: 0.7;
        }

        /* ══════ KEYFRAMES ══════ */
        @keyframes slowZoom {
            0%   { transform: scale(1.0); }
            100% { transform: scale(1.08); }
        }
        @keyframes glowPulse {
            0%, 100% { opacity: 0.7; transform: scale(1); }
            50%       { opacity: 1.0; transform: scale(1.05); }
        }
        @keyframes shinePass {
            0%        { left: -80%; }
            60%, 100% { left: 140%; }
        }
        @keyframes spinRing {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes cardIn {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes shake {
            10%, 90%      { transform: translateX(-2px); }
            20%, 80%      { transform: translateX(4px); }
            30%, 50%, 70% { transform: translateX(-5px); }
            40%, 60%      { transform: translateX(5px); }
        }
        @keyframes shimmer {
            0%        { left: -100%; }
            55%, 100% { left: 130%; }
        }
    </style>
</head>
<body>

    <!-- الخلفية: صورة المركز + blur + تلوين -->
    <div class="bg-photo"></div>
    <div class="bg-blur"></div>
    <div class="bg-tint"></div>

    <!-- الكارد -->
    <div class="login-wrap">
        <div class="card-glow"></div>
        <div class="login-card">
            <div class="card-shine"></div>

            <!-- الشعار -->
            <div class="logo-wrap">
                <div class="logo-ring">
                    <div class="ring-spin"></div>
                    <div class="ring-static"></div>
                    <div class="logo-center">
                        <img src="/logo.jpg" alt="مطمئنة"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="logo-fallback">🧠</div>
                    </div>
                </div>
                <div class="logo-title">
                    مركز مطمئنة<br>
                    <span>للاستشارات النفسية</span>
                </div>
            </div>

            <!-- فاصل -->
            <div class="divider">
                <div class="div-line"></div>
                <div class="div-text">تسجيل الدخول</div>
                <div class="div-line"></div>
            </div>

            {{ $slot }}

            <div class="card-footer">
                <p>
                    مركز مطمئنة للاستشارات النفسية
                    <span class="gold-dot"></span>
                    جميع الحقوق محفوظة &copy; {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
