<div style="min-height:80vh; padding:1.5rem 2rem;">
<div style="max-width:1400px; margin:0 auto; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; animation:fadeIn 0.5s ease;">

    <!-- رأس الإطار -->
    <div style="padding:1.25rem 1.75rem; border-bottom:1px solid var(--border); background:#fafbfc; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:42px; height:42px; background:var(--primary-glow); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">📅</div>
            <h1 style="font-size:1.4rem; font-weight:900; color:var(--primary); margin:0; font-family:'Tajawal',sans-serif;">جدول المواعيد والعيادات</h1>
        </div>
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="background:#fff; padding:0.4rem 1rem; border:1px solid var(--border); border-radius:50px; font-size:0.82rem; color:var(--text-dim); font-weight:700;">
                📅 {{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
            </div>
            <button class="btn btn-primary">➕ حجز موعد جديد</button>
        </div>
    </div>

    <!-- المحتوى -->
    <div style="padding:1.75rem;">

        <!-- فلاتر -->
        <div style="background:#f8fafc; border:1px solid var(--border); border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem;">
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center;">
                <div style="position:relative; flex:1; min-width:200px;">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="بحث باسم العميل..."
                        class="form-input" style="padding-right:2.8rem;">
                    <span style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); opacity:0.4;">🔍</span>
                </div>
                <select wire:model="selectedClinic" class="form-input" style="width:200px;">
                    <option value="">جميع العيادات</option>
                    @foreach($clinics as $clinic)
                        <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                    @endforeach
                </select>
                <input type="date" wire:model="filterDate" class="form-input" style="width:170px;">
                <button wire:click="$refresh"
                    style="padding:0.6rem 1.2rem; background:var(--primary); border:none; border-radius:8px; cursor:pointer; display:flex; align-items:center; gap:0.4rem; font-size:0.85rem; color:#fff; font-weight:800;">
                    🔍 بحث
                </button>
            </div>
        </div>

        <!-- قائمة المواعيد -->
        <div style="display:grid; grid-template-columns:2fr 1fr; gap:1.5rem;">

            <!-- الجدول -->
            <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;">
                <div style="padding:0.9rem 1.25rem; background:#fafbfc; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
                    <h3 style="font-size:0.95rem; font-weight:800; color:var(--text); margin:0; display:flex; align-items:center; gap:0.5rem;">
                        📅 قائمة المواعيد المحجوزة
                    </h3>
                    <span class="badge badge-amber">{{ $appointments->total() }} موعد</span>
                </div>
                <div style="padding:1rem; display:flex; flex-direction:column; gap:0.75rem;">
                    @forelse($appointments as $app)
                    <div style="border:1px solid var(--border); border-radius:10px; padding:1rem 1.25rem; display:flex; justify-content:space-between; align-items:center; transition:all 0.2s; background:#fff; {{ $app->status == 1 ? 'opacity:0.5;' : '' }}"
                        onmouseover="this.style.borderColor='var(--primary)'; this.style.background='#fef5f5'"
                        onmouseout="this.style.borderColor='var(--border)'; this.style.background='#fff'">
                        <div style="display:flex; gap:1rem; align-items:center;">
                            <div style="background:#e3f2fd; color:#1565c0; border-radius:10px; padding:0.5rem 1rem; text-align:center; font-weight:900; font-size:1rem; border:1px solid #bbdefb; min-width:80px; line-height:1.3; direction:ltr; unicode-bidi:isolate;">
                                {{ $app->rec_time ?: '--:--' }}<br>
                                <span style="font-size:0.68rem; font-weight:700; opacity:0.7;">{{ fmt_date($app->rec_date) }}</span>
                            </div>
                            <div>
                                <div style="font-size:1rem; font-weight:800; color:var(--text); margin-bottom:0.2rem;">{{ $app->patient_name ?: 'عميل غير مسجل' }}</div>
                                <div style="font-size:0.82rem; color:var(--text-dim);">{{ $app->clinic_name ?: 'عيادة غير محددة' }} • <span style="color:#1565c0;">#{{ $app->id }}</span></div>
                            </div>
                        </div>
                        <div>
                            @if($app->status == 1)
                                <span class="badge badge-gray">منتهي</span>
                            @else
                                <span class="badge badge-amber">محجوز</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center; padding:4rem 2rem; color:var(--text-muted);">
                        <div style="font-size:3rem; margin-bottom:1rem; opacity:0.2;">🗓️</div>
                        <div style="font-weight:800;">لا توجد مواعيد محجوزة</div>
                    </div>
                    @endforelse
                </div>
                @if($appointments->hasPages())
                <div style="padding:0.75rem 1rem; border-top:1px solid var(--border); background:#fafbfc;">
                    <div class="custom-pagination">{{ $appointments->links() }}</div>
                </div>
                @endif
            </div>

            <!-- الإحصائيات -->
            <div style="display:flex; flex-direction:column; gap:1.25rem;">
                <div style="background:linear-gradient(135deg,var(--gold),#f39c12); border-radius:12px; padding:1.5rem; color:#fff; position:relative; overflow:hidden;">
                    <div style="position:absolute; left:-15px; bottom:-15px; font-size:6rem; opacity:0.1;">📋</div>
                    <div style="font-size:0.8rem; font-weight:700; opacity:0.85; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:1px;">Appointments Digest</div>
                    <div style="font-size:1.1rem; font-weight:900; margin-bottom:1.25rem;">{{ now()->locale('ar')->isoFormat('dddd، D MMMM') }}</div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; border-top:1px solid rgba(255,255,255,0.2); padding-top:1rem;">
                        <div>
                            <div style="font-size:2rem; font-weight:900;">{{ $appointments->total() }}</div>
                            <div style="font-size:0.7rem; opacity:0.75; font-weight:700;">TOTAL PENDING</div>
                        </div>
                        <div>
                            <div style="font-size:2rem; font-weight:900;">{{ $clinics->count() }}</div>
                            <div style="font-size:0.7rem; opacity:0.75; font-weight:700;">ACTIVE CLINICS</div>
                        </div>
                    </div>
                </div>

                <!-- العيادات النشطة -->
                <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden;">
                    <div style="padding:0.8rem 1.25rem; background:#fafbfc; border-bottom:1px solid var(--border);">
                        <span style="font-weight:800; color:var(--text); font-size:0.9rem;">🏥 العيادات</span>
                    </div>
                    <div style="padding:0.75rem;">
                        @foreach($clinics as $clinic)
                        <div style="padding:0.6rem 0.75rem; border-radius:8px; font-size:0.88rem; font-weight:700; color:var(--text-dim); display:flex; align-items:center; gap:0.5rem; cursor:pointer; transition:background 0.15s;"
                            onmouseover="this.style.background='#fef5f5'; this.style.color='var(--primary)'"
                            onmouseout="this.style.background='transparent'; this.style.color='var(--text-dim)'">
                            <span style="width:8px; height:8px; background:var(--primary); border-radius:50%; display:inline-block; opacity:0.5;"></span>
                            {{ $clinic->name }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
