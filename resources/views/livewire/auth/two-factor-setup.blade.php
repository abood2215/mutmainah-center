<div class="pg-outer" style="min-height:80vh; padding:2rem; display:flex; align-items:center; justify-content:center;">
<div style="width:100%; max-width:480px; background:#fff; border:1px solid var(--border); border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08);">

    {{-- رأس --}}
    <div style="background:var(--navy); padding:1.25rem 1.5rem; border-bottom:3px solid var(--gold); display:flex; align-items:center; gap:0.75rem;">
        <div style="width:38px; height:38px; background:rgba(255,255,255,0.15); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">🔐</div>
        <div>
            <div style="color:#fff; font-weight:900; font-size:1rem;">المصادقة الثنائية (2FA)</div>
            <div style="color:rgba(255,255,255,0.55); font-size:0.75rem;">Two-Factor Authentication</div>
        </div>
    </div>

    <div style="padding:1.75rem;">

        @if($success)
        {{-- نجاح التفعيل --}}
        <div style="text-align:center; padding:1rem 0;">
            <div style="font-size:3rem; margin-bottom:1rem;">✅</div>
            <div style="font-size:1.1rem; font-weight:900; color:#059669; margin-bottom:0.5rem;">تم تفعيل المصادقة الثنائية!</div>
            <div style="font-size:0.85rem; color:#64748b; margin-bottom:1.5rem;">حسابك محمي الآن. ستُطلب الرمز في كل تسجيل دخول.</div>
            <a href="{{ route('dashboard') }}" style="display:inline-block; background:var(--primary); color:#fff; text-decoration:none; padding:0.65rem 2rem; border-radius:8px; font-weight:800; font-size:0.9rem;">العودة للداشبورد</a>
        </div>

        @elseif($enabled)
        {{-- 2FA مفعّل بالفعل --}}
        <div style="text-align:center; padding:0.5rem 0 1rem;">
            <div style="font-size:2.5rem; margin-bottom:0.75rem;">🛡️</div>
            <div style="font-size:1rem; font-weight:900; color:#059669; margin-bottom:0.4rem;">المصادقة الثنائية مفعّلة</div>
            <div style="font-size:0.82rem; color:#64748b; margin-bottom:1.5rem;">حسابك محمي بطبقة أمان إضافية.</div>
        </div>
        <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:1rem; margin-bottom:1.25rem; font-size:0.83rem; color:#dc2626; font-weight:700; text-align:center;">
            ⚠️ إلغاء التفعيل سيجعل حسابك أقل أماناً
        </div>
        <button wire:click="disable" wire:confirm="هل أنت متأكد من إلغاء تفعيل المصادقة الثنائية؟"
            style="width:100%; padding:0.75rem; background:#dc2626; color:#fff; border:none; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.9rem; font-weight:800; cursor:pointer;">
            إلغاء تفعيل 2FA
        </button>

        @else
        {{-- إعداد 2FA --}}
        <div style="margin-bottom:1.25rem; font-size:0.85rem; color:#475569; font-weight:600; line-height:1.7;">
            <strong style="color:var(--navy);">الخطوات:</strong><br>
            1. حمّل تطبيق <strong>Google Authenticator</strong> أو <strong>Authy</strong><br>
            2. امسح الـ QR Code أدناه<br>
            3. أدخل الرمز المكوّن من 6 أرقام للتأكيد
        </div>

        {{-- QR Code --}}
        <div style="text-align:center; background:#f8fafc; border:1px solid var(--border); border-radius:12px; padding:1.25rem; margin-bottom:1.25rem;">
            {!! $qrUrl !!}
            <div style="margin-top:0.75rem; font-size:0.72rem; color:#94a3b8; font-weight:600;">أو أدخل هذا الرمز يدوياً في التطبيق</div>
            <div style="margin-top:0.35rem; font-family:monospace; font-size:0.85rem; font-weight:900; color:var(--navy); letter-spacing:2px; background:#e2e8f0; padding:0.4rem 0.75rem; border-radius:6px; display:inline-block; direction:ltr;">{{ $secret }}</div>
        </div>

        @if($failed)
        <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:0.65rem 1rem; margin-bottom:1rem; font-size:0.85rem; color:#dc2626; font-weight:700;">⚠️ {{ $message }}</div>
        @endif

        {{-- إدخال الرمز --}}
        <div style="margin-bottom:1rem;">
            <label style="display:block; font-size:0.82rem; font-weight:800; color:#374151; margin-bottom:0.5rem;">أدخل رمز التحقق للتأكيد</label>
            <input wire:model="code" wire:keydown.enter="confirm"
                   type="text" inputmode="numeric" maxlength="6" placeholder="● ● ● ● ● ●"
                   style="width:100%; padding:0.75rem 1rem; border:2px solid #e2e8f0; border-radius:8px; font-size:1.3rem; font-weight:900; text-align:center; letter-spacing:0.5rem; font-family:'Tajawal',sans-serif; outline:none; direction:ltr;"
                   onfocus="this.style.borderColor='#8b1c2b'" onblur="this.style.borderColor='#e2e8f0'">
        </div>

        <button wire:click="confirm" wire:loading.attr="disabled"
            style="width:100%; padding:0.8rem; background:var(--primary); color:#fff; border:none; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.95rem; font-weight:900; cursor:pointer;">
            <span wire:loading.remove wire:target="confirm">🔐 تفعيل المصادقة الثنائية</span>
            <span wire:loading wire:target="confirm">جارٍ التحقق...</span>
        </button>
        @endif

    </div>
</div>
</div>
