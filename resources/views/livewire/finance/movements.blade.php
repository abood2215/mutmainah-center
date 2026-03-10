<div style="min-height:80vh; padding:1.5rem 2rem; display:flex; justify-content:center; align-items:flex-start;">
<div style="width:100%; max-width:820px; animation:fadeIn 0.4s ease;">

@php
$days   = range(1, 31);
$months = [1=>'يناير',2=>'فبراير',3=>'مارس',4=>'أبريل',5=>'مايو',6=>'يونيو',
           7=>'يوليو',8=>'أغسطس',9=>'سبتمبر',10=>'أكتوبر',11=>'نوفمبر',12=>'ديسمبر'];
$years  = range(2000, now()->year + 1);
@endphp

{{-- رسالة النجاح --}}
@if($saved)
<div style="background:#f0fdf4; border:1px solid #86efac; border-radius:10px; padding:0.75rem 1.25rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; color:#166534; font-weight:800;">
    ✅ {{ $savedMessage }}
</div>
@endif

{{-- بطاقة النموذج --}}
<div style="border-radius:14px; overflow:hidden; border:2px solid var(--gold); box-shadow:0 8px 25px rgba(0,0,0,0.1);">

    {{-- رأس --}}
    <div style="background:var(--navy); padding:0.9rem 1.5rem; text-align:center;">
        <span style="color:#fbbf24; font-size:1.05rem; font-weight:900; font-family:'Tajawal',sans-serif;">
            الحركات المالية : Financial Movements
        </span>
    </div>

    {{-- النموذج --}}
    <div style="background:#fff; padding:0;">
        <table style="width:100%; border-collapse:collapse;">

            {{-- العميل --}}
            <tr style="border-bottom:1px solid #f0f2f5;">
                <td style="padding:0.75rem 1.5rem; text-align:left; font-weight:900; color:#8b6914; font-size:0.88rem; width:220px; background:#fafafa; border-left:2px solid #f0f2f5;">
                    Patient : العميل <span style="color:var(--primary);">*</span>
                </td>
                <td style="padding:0.65rem 1.25rem; position:relative;">
                    <input type="text" wire:model.live.debounce.300ms="clientSearch"
                        placeholder="ابحث باسم العميل..."
                        style="width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:6px; font-family:'Tajawal',sans-serif; font-size:0.9rem; outline:none;"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d1d5db'">
                    @if($searchResults)
                    <div style="position:absolute; top:100%; right:1.25rem; left:1.25rem; background:#fff; border:1px solid #e5e7eb; border-radius:8px; box-shadow:0 8px 20px rgba(0,0,0,0.1); z-index:50; max-height:220px; overflow-y:auto;">
                        @foreach($searchResults as $r)
                        <div wire:click="selectClient({{ $r->id }}, '{{ addslashes($r->full_name) }}')"
                            style="padding:0.6rem 1rem; cursor:pointer; border-bottom:1px solid #f9fafb; display:flex; justify-content:space-between; align-items:center;"
                            onmouseover="this.style.background='#fef5f5'" onmouseout="this.style.background='#fff'">
                            <span style="font-weight:800; color:var(--navy);">{{ $r->full_name }}</span>
                            <span style="display:flex; gap:0.75rem; align-items:center;">
                                <span style="font-size:0.78rem; color:#6b7280;">{{ $r->phone }}</span>
                                <span style="font-size:0.78rem; color:#1565c0; font-weight:700;">#{{ $r->file_id }}</span>
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </td>
            </tr>

            {{-- نوع الحركة --}}
            <tr style="border-bottom:1px solid #f0f2f5;">
                <td style="padding:0.75rem 1.5rem; text-align:left; font-weight:900; color:#8b6914; font-size:0.88rem; background:#fafafa; border-left:2px solid #f0f2f5;">
                    Type : نوع الحركة <span style="color:var(--primary);">*</span>
                </td>
                <td style="padding:0.65rem 1.25rem;">
                    <select wire:model="moveType"
                        style="padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:6px; font-family:'Tajawal',sans-serif; font-size:0.9rem; min-width:200px; outline:none;">
                        <option value="receipt">سند قبض : Receipt</option>
                        <option value="payment">سند صرف : Payment</option>
                    </select>
                </td>
            </tr>

            {{-- المبلغ --}}
            <tr style="border-bottom:1px solid #f0f2f5;">
                <td style="padding:0.75rem 1.5rem; text-align:left; font-weight:900; color:#8b6914; font-size:0.88rem; background:#fafafa; border-left:2px solid #f0f2f5;">
                    Amount : المبلغ <span style="color:var(--primary);">*</span>
                </td>
                <td style="padding:0.65rem 1.25rem;">
                    <input type="number" wire:model="amount" min="0" step="0.001"
                        placeholder="0.000"
                        style="padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:6px; font-family:'Inter'; font-size:0.9rem; width:180px; outline:none; direction:ltr;"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d1d5db'">
                </td>
            </tr>

            {{-- طريقة الدفع --}}
            <tr style="border-bottom:1px solid #f0f2f5;">
                <td style="padding:0.75rem 1.5rem; text-align:left; font-weight:900; color:#8b6914; font-size:0.88rem; background:#fafafa; border-left:2px solid #f0f2f5;">
                    P.Method : طريقة الدفع <span style="color:var(--primary);">*</span>
                </td>
                <td style="padding:0.65rem 1.25rem;">
                    <select wire:model="paymentMethod"
                        style="padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:6px; font-family:'Tajawal',sans-serif; font-size:0.9rem; min-width:200px; outline:none;">
                        @foreach($paymentMethods as $code => $info)
                        <option value="{{ $code }}">{{ $info['ar'] }} : {{ $info['en'] }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            {{-- التاريخ --}}
            <tr style="border-bottom:1px solid #f0f2f5;">
                <td style="padding:0.75rem 1.5rem; text-align:left; font-weight:900; color:#8b6914; font-size:0.88rem; background:#fafafa; border-left:2px solid #f0f2f5;">
                    Date : التاريخ <span style="color:var(--primary);">*</span>
                </td>
                <td style="padding:0.65rem 1.25rem;">
                    <div style="display:flex; gap:0.4rem; direction:ltr; align-items:center;">
                        <select wire:model="day" style="padding:0.45rem 0.5rem; border:1px solid #d1d5db; border-radius:6px; font-family:'Inter'; font-size:0.88rem; outline:none;">
                            @foreach($days as $d)<option value="{{ $d }}">{{ $d }}</option>@endforeach
                        </select>
                        <select wire:model="month" style="padding:0.45rem 0.5rem; border:1px solid #d1d5db; border-radius:6px; font-family:'Tajawal',sans-serif; font-size:0.88rem; outline:none; min-width:90px;">
                            @foreach($months as $n => $name)<option value="{{ $n }}">{{ $name }}</option>@endforeach
                        </select>
                        <select wire:model="year" style="padding:0.45rem 0.5rem; border:1px solid #d1d5db; border-radius:6px; font-family:'Inter'; font-size:0.88rem; outline:none;">
                            @foreach($years as $y)<option value="{{ $y }}">{{ $y }}</option>@endforeach
                        </select>
                    </div>
                </td>
            </tr>

            {{-- البيان --}}
            <tr style="border-bottom:1px solid #f0f2f5;">
                <td style="padding:0.75rem 1.5rem; text-align:left; font-weight:900; color:#8b6914; font-size:0.88rem; background:#fafafa; border-left:2px solid #f0f2f5; vertical-align:top;">
                    Desc : البيان
                </td>
                <td style="padding:0.65rem 1.25rem;">
                    <textarea wire:model="desc" rows="3"
                        style="width:100%; padding:0.5rem 0.75rem; border:1px solid #d1d5db; border-radius:6px; font-family:'Tajawal',sans-serif; font-size:0.9rem; resize:vertical; outline:none;"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                </td>
            </tr>

        </table>
    </div>

    {{-- أزرار --}}
    <div style="background:var(--navy); padding:0.75rem 1.5rem; display:flex; align-items:center; justify-content:center; gap:1rem;">
        <button wire:click="save"
            style="padding:0.5rem 2rem; background:#2563eb; color:#fff; border:none; border-radius:6px; font-weight:900; font-size:0.9rem; font-family:'Tajawal',sans-serif; cursor:pointer; transition:background 0.2s;"
            onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
            حفظ : Save
        </button>
        <button wire:click="resetForm"
            style="padding:0.5rem 1.5rem; background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.3); border-radius:6px; font-weight:800; font-size:0.9rem; font-family:'Tajawal',sans-serif; cursor:pointer;"
            onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
            إستعادة : Reset
        </button>
    </div>

    {{-- روابط سفلية --}}
    <div style="background:#f1f5f9; padding:0.55rem 1.5rem; display:flex; gap:1.5rem; border-top:1px solid #e2e8f0;">
        <a href="{{ route('dashboard') }}" wire:navigate style="color:#1565c0; font-weight:800; font-size:0.83rem; text-decoration:none;">الرئيسية : Home</a>
        <span style="color:#cbd5e1;">|</span>
        <a href="{{ route('finance.invoices') }}" wire:navigate style="color:#1565c0; font-weight:800; font-size:0.83rem; text-decoration:none;">الحركات المدخلة : Movements Display</a>
    </div>

</div>

</div>
</div>
