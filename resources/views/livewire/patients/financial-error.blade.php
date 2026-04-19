<div class="pg-outer" style="min-height:60vh; display:flex; align-items:center; justify-content:center; padding:2rem;">
    <div style="text-align:center; max-width:480px;">
        <div style="font-size:3rem; margin-bottom:1rem;">⚠️</div>
        <h2 style="font-size:1.2rem; font-weight:900; color:var(--primary); margin-bottom:0.5rem;">تعذّر تحميل البيان المالي</h2>
        <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:1.5rem;">
            حدث خطأ أثناء معالجة البيانات. يرجى المحاولة مرة أخرى أو التواصل مع الدعم.
        </p>
        @if(config('app.debug'))
        <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:1rem; text-align:right; font-size:0.75rem; color:#991b1b; font-family:monospace; margin-bottom:1.5rem; overflow-wrap:break-word;">
            {{ $error }}
        </div>
        @endif
        <a href="javascript:history.back()"
            style="display:inline-block; background:var(--primary); color:#fff; padding:0.65rem 1.5rem; border-radius:10px; font-weight:800; text-decoration:none;">
            رجوع
        </a>
    </div>
</div>
