<div class="pg-outer" style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:700px; margin:0 auto; animation:fadeIn 0.4s ease;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.75rem;">
        <div>
            <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">إعادة طباعة سند</h1>
            <div style="font-size:0.8rem; color:#6b7280; margin-top:0.2rem; font-weight:600;">Voucher Reprint</div>
        </div>
        <a href="{{ route('finance.movements') }}" wire:navigate
            style="padding:0.45rem 1rem; background:#fff; color:#374151; border:1px solid #e5e7eb; border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:700; font-size:0.82rem; text-decoration:none;">
            ← الحركات المالية
        </a>
    </div>

    {{-- Search Box --}}
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:1.5rem; box-shadow:0 2px 10px rgba(0,0,0,0.06); margin-bottom:1.5rem;">
        <label style="display:block; font-size:0.8rem; font-weight:800; color:#374151; margin-bottom:0.6rem; font-family:'Tajawal',sans-serif;">
            رقم السند / Voucher Number
        </label>
        <div style="display:flex; gap:0.75rem;">
            <div style="position:relative; flex:1;">
                <input type="text" wire:model="voucherNo" wire:keydown.enter="search"
                    placeholder="مثال: 115820 أو #115820"
                    style="width:100%; padding:0.75rem 1rem 0.75rem 2.5rem; border:2px solid #e5e7eb; border-radius:10px; font-family:'Tajawal',sans-serif; font-size:1rem; font-weight:700; outline:none; color:#111827; transition:border-color 0.2s; direction:ltr; text-align:center;"
                    onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">
                <span style="position:absolute; left:0.85rem; top:50%; transform:translateY(-50%); font-size:1rem; opacity:0.4;">#</span>
            </div>
            <button wire:click="search" wire:loading.attr="disabled"
                style="padding:0.75rem 2rem; background:var(--primary); color:#fff; border:none; border-radius:10px; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.95rem; cursor:pointer; white-space:nowrap; box-shadow:0 2px 8px rgba(139,28,43,0.25);">
                <span wire:loading.remove wire:target="search">🔍 بحث</span>
                <span wire:loading wire:target="search">...</span>
            </button>
        </div>
        @if($error)
        <div style="margin-top:0.75rem; color:#dc2626; font-size:0.88rem; font-weight:700; font-family:'Tajawal',sans-serif;">⚠️ {{ $error }}</div>
        @endif
    </div>

    {{-- Voucher Details --}}
    @if($voucher)
    @php $isReceipt = $voucher->status == 1; @endphp
    <div style="background:#fff; border:2px solid {{ $isReceipt ? '#86efac' : '#fca5a5' }}; border-radius:14px; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.08); animation:fadeIn 0.3s ease;">

        {{-- Voucher Header --}}
        <div style="background:{{ $isReceipt ? '#15803d' : '#b91c1c' }}; padding:1rem 1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <span style="font-size:1.4rem;">{{ $isReceipt ? '📥' : '📤' }}</span>
                <div>
                    <div style="color:#fff; font-weight:900; font-size:1rem; font-family:'Tajawal',sans-serif;">{{ $isReceipt ? 'سند قبض' : 'سند صرف' }}</div>
                    <div style="color:rgba(255,255,255,0.7); font-size:0.7rem; font-weight:700; letter-spacing:1px;">{{ $isReceipt ? 'CASH RECEIPT VOUCHER' : 'PAYMENT VOUCHER' }}</div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="text-align:center; background:rgba(0,0,0,0.2); border-radius:8px; padding:0.5rem 1rem;">
                    <div style="color:rgba(255,255,255,0.6); font-size:0.65rem; font-weight:700; letter-spacing:2px;">VOUCHER NO.</div>
                    <div style="color:#fff; font-size:1.4rem; font-weight:900;">#{{ $voucher->id }}</div>
                </div>
                <a href="{{ route('finance.movement-print', $voucher->id) }}" target="_blank"
                    style="padding:0.6rem 1.25rem; background:#fff; color:{{ $isReceipt ? '#15803d' : '#b91c1c' }}; border-radius:8px; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.88rem; text-decoration:none; display:flex; align-items:center; gap:0.4rem; white-space:nowrap; box-shadow:0 2px 6px rgba(0,0,0,0.15);">
                    🖨️ طباعة
                </a>
            </div>
        </div>

        {{-- Amount --}}
        <div style="padding:1.25rem 1.5rem; background:{{ $isReceipt ? '#f0fdf4' : '#fff5f5' }}; border-bottom:1px solid {{ $isReceipt ? '#bbf7d0' : '#fecaca' }}; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div>
                <div style="font-size:0.7rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:4px;">Amount</div>
                <div style="font-size:2.2rem; font-weight:900; color:{{ $isReceipt ? '#15803d' : '#b91c1c' }}; line-height:1; font-family:'Inter',monospace;">
                    <span style="font-size:1rem; color:#6b7280; font-weight:600;">KD </span>{{ number_format($voucher->mov_amount, 3) }}
                </div>
            </div>
            <div style="background:#fff; border:1.5px solid {{ $isReceipt ? '#86efac' : '#fca5a5' }}; border-radius:10px; padding:0.65rem 1.25rem; text-align:center;">
                <div style="font-size:0.65rem; font-weight:700; color:#9ca3af; letter-spacing:1.5px; margin-bottom:3px;">PAYMENT METHOD</div>
                <div style="font-size:1rem; font-weight:800; color:#111827;">{{ $payMethods[$voucher->payment_method] ?? 'Cash' }}</div>
            </div>
        </div>

        {{-- Info Rows --}}
        <div style="padding:0.5rem 1.5rem;">

            @php
            $rows = [
                ['key' => 'Client',         'val' => ($voucher->patient_name ?? $voucher->acck_name ?? '—') . ($voucher->patient_file ? ' &nbsp;<span style="font-size:0.78rem;color:#9ca3af;">File #'.$voucher->patient_file.'</span>' : '')],
                ['key' => 'Phone',          'val' => $voucher->patient_phone ?: null],
                ['key' => 'Date & Time',    'val' => $voucher->pdate . ($voucher->ptime ? ' &nbsp;|&nbsp; ' . $voucher->ptime : '')],
                ['key' => 'Description',    'val' => $descClean ?: null],
                ['key' => 'Reference No.',  'val' => $refNo ? '<span style="background:#dbeafe;color:#1d4ed8;font-size:0.82rem;font-weight:800;padding:2px 10px;border-radius:20px;border:1px solid #bfdbfe;">' . $refNo . '</span>' : null],
                ['key' => 'Recorded By',    'val' => trim($voucher->emp_name) ?: '—'],
            ];
            @endphp

            @foreach($rows as $row)
            @if($row['val'] !== null)
            <div style="display:flex; align-items:center; gap:1rem; padding:0.85rem 0; border-bottom:1px solid #f3f4f6;">
                <div style="font-size:0.72rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:1px; min-width:110px; flex-shrink:0;">{{ $row['key'] }}</div>
                <div style="font-size:0.95rem; font-weight:700; color:#111827; flex:1;">{!! $row['val'] !!}</div>
            </div>
            @endif
            @endforeach

        </div>

        <div style="padding:0.75rem 1.5rem; background:#f9fafb; text-align:center;">
            <a href="{{ route('finance.movement-print', $voucher->id) }}" target="_blank"
                style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 2.5rem; background:{{ $isReceipt ? '#15803d' : '#b91c1c' }}; color:#fff; border-radius:9px; font-family:'Tajawal',sans-serif; font-weight:900; font-size:0.95rem; text-decoration:none; box-shadow:0 3px 10px rgba(0,0,0,0.15);">
                🖨️ فتح وطباعة الوصل
            </a>
        </div>

    </div>
    @endif

</div>
</div>
