<div>
    @if($failed)
    <div class="error-box" role="alert">
        <div class="error-icon">✕</div>
        <span>{{ $errorMsg ?: 'اسم المستخدم أو كلمة المرور غير صحيحة' }}</span>
    </div>
    @endif

    <div class="form-group">
        <label class="form-label" for="username">اسم المستخدم</label>
        <div class="input-wrap">
            <input
                type="text"
                id="username"
                wire:model="username"
                wire:keydown.enter="login"
                class="form-input"
                placeholder="أدخل اسم المستخدم"
                autocomplete="username"
                autofocus
                dir="ltr"
                style="text-align:right;">
            <span class="input-icon">👤</span>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label" for="password">كلمة المرور</label>
        <div class="input-wrap">
            <input
                type="password"
                id="password"
                wire:model="password"
                wire:keydown.enter="login"
                class="form-input"
                placeholder="••••••••"
                autocomplete="current-password"
                dir="ltr"
                style="text-align:right;">
            <span class="input-icon">🔒</span>
        </div>
    </div>

    <div class="btn-login-wrap">
        <button wire:click="login" wire:loading.attr="disabled" class="btn-login">
            <div class="btn-shimmer"></div>
            <svg wire:loading wire:target="login" class="spin-icon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,0.3)" stroke-width="2.5"/>
                <path d="M12 2a10 10 0 0 1 10 10" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
            <span wire:loading.remove wire:target="login">دخول إلى النظام</span>
            <span wire:loading wire:target="login">جارٍ التحقق...</span>
        </button>
    </div>
</div>
