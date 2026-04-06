<div style="display:flex; flex-direction:column; align-items:center; gap:1.5rem;">

    <div style="text-align:center;">
        <div style="font-size:3rem; margin-bottom:0.5rem;">🔐</div>
        <h2 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0;">التحقق بخطوتين</h2>
        <p style="font-size:0.85rem; color:#64748b; margin-top:0.5rem; font-weight:600;">أدخل الرمز من تطبيق Google Authenticator</p>
    </div>

    @if($failed)
    <div style="width:100%; background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:0.75rem 1rem; display:flex; align-items:center; gap:0.75rem;">
        <span style="font-size:1.1rem;">⚠️</span>
        <span style="font-size:0.88rem; font-weight:700; color:#dc2626;">{{ $message }}</span>
    </div>
    @endif

    <div style="width:100%;">
        <label style="display:block; font-size:0.82rem; font-weight:800; color:#374151; margin-bottom:0.5rem;">الرمز المكوّن من 6 أرقام</label>
        <input wire:model="code"
               wire:keydown.enter="verify"
               type="text"
               inputmode="numeric"
               maxlength="6"
               placeholder="● ● ● ● ● ●"
               autofocus
               style="width:100%; padding:0.85rem 1rem; border:2px solid #e2e8f0; border-radius:10px; font-family:'Tajawal',sans-serif; font-size:1.5rem; font-weight:900; text-align:center; letter-spacing:0.5rem; outline:none; transition:border 0.2s; direction:ltr;"
               onfocus="this.style.borderColor='#8b1c2b'"
               onblur="this.style.borderColor='#e2e8f0'">
    </div>

    <button wire:click="verify" wire:loading.attr="disabled"
        style="width:100%; padding:0.85rem; background:#8b1c2b; color:#fff; border:none; border-radius:10px; font-family:'Tajawal',sans-serif; font-size:1rem; font-weight:900; cursor:pointer; transition:opacity 0.2s;"
        onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
        <span wire:loading.remove wire:target="verify">✓ تحقق</span>
        <span wire:loading wire:target="verify">جارٍ التحقق...</span>
    </button>

    <div style="font-size:0.8rem; color:#94a3b8; text-align:center; font-weight:600;">
        افتح تطبيق <strong>Google Authenticator</strong> أو <strong>Authy</strong> وأدخل الرمز الظاهر
    </div>

    <form method="POST" action="{{ route('logout') }}" style="width:100%;">
        @csrf
        <button type="submit" style="width:100%; padding:0.6rem; background:transparent; border:1px solid #e2e8f0; border-radius:8px; font-family:'Tajawal',sans-serif; font-size:0.85rem; color:#64748b; cursor:pointer;">
            تسجيل الخروج
        </button>
    </form>

</div>
