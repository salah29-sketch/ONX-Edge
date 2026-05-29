{{-- smart.blade.php --}}
@extends('layouts.front_tailwind')
@section('title', 'احجز مشروعك | ONX Edge')

@section('content')

@push('styles')
<style>
*,*::before,*::after{box-sizing:border-box;}
[x-cloak]{display:none!important;}
:root{
    --or:#f97316;--or-dim:rgba(249,115,22,.12);--or-glow:rgba(249,115,22,.35);
    --glass:rgba(255,255,255,.03);--border:rgba(255,255,255,.07);
    --muted:rgba(255,255,255,.45);--dim:rgba(255,255,255,.22);
    --r:14px;--rs:10px;
}
.fi{width:100%;background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:var(--rs);padding:11px 14px;font-size:13px;color:#fff;font-family:inherit;outline:none;transition:border-color .2s,box-shadow .2s;-webkit-appearance:none;appearance:none;}
.fi:focus{border-color:var(--or);box-shadow:0 0 0 3px rgba(249,115,22,.12);}
.fi::placeholder{color:var(--dim);}
.fi option{background:#111;}
.fl{display:block;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:7px;}
.gc{background:var(--glass);backdrop-filter:blur(20px);border:1px solid var(--border);border-radius:var(--r);overflow:visible;}
.sh{font-size:11px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--or);margin-bottom:14px;display:flex;align-items:center;gap:8px;}
.sh::after{content:'';flex:1;height:1px;background:linear-gradient(to left,transparent,var(--or-dim));}

/* service cards */
.sc{position:relative;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--rs);padding:18px 12px;text-align:center;cursor:pointer;transition:all .25s;display:block;width:100%;}
.sc:hover{border-color:rgba(249,115,22,.4);background:rgba(249,115,22,.04);}
.sc.on{border-color:var(--or);background:var(--or-dim);box-shadow:0 0 24px var(--or-glow);}

/* package cards */
.pc{position:relative;border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:24px 16px 20px;cursor:pointer;transition:border-color .2s,background .2s,transform .2s,box-shadow .2s;background:rgba(255,255,255,.03);text-align:center;display:flex;flex-direction:column;align-items:center;width:100%;font-family:inherit;}
.pc:hover{border-color:rgba(249,115,22,.25);transform:translateY(-2px);}
.pc.on{border-color:var(--or);background:var(--or-dim);transform:translateY(-4px);box-shadow:0 0 0 1px var(--or),0 12px 40px rgba(249,115,22,.2);}
.pc-line{position:absolute;top:0;left:50%;transform:translateX(-50%);width:40px;height:2px;background:var(--or);border-radius:0 0 4px 4px;transition:width .3s;}
.pc.on .pc-line{width:80px;}
.pc-badge{position:absolute;top:10px;right:10px;background:rgba(249,115,22,.15);border:1px solid rgba(249,115,22,.35);color:var(--or);font-size:9px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:3px 8px;border-radius:6px;white-space:nowrap;}
.pc-icon{width:48px;height:48px;border-radius:12px;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.03);display:flex;align-items:center;justify-content:center;font-size:20px;margin-top:8px;margin-bottom:12px;transition:border-color .2s,background .2s;}
.pc.on .pc-icon{border-color:rgba(249,115,22,.4);background:rgba(249,115,22,.1);}
.pc-prices{margin-top:auto;width:100%;border-top:1px solid rgba(255,255,255,.07);padding-top:14px;display:flex;flex-direction:column;align-items:center;min-height:54px;justify-content:flex-end;}
.pc-old{font-size:11px;color:rgba(255,255,255,.25);text-decoration:line-through;margin-bottom:4px;height:16px;}
.pc-price{font-size:22px;font-weight:900;color:var(--or);letter-spacing:-.01em;line-height:1;}
.pc-price span{font-size:12px;font-weight:500;color:rgba(249,115,22,.7);margin-right:3px;}

.pkg-grid{display:grid !important;gap:12px;}

/* calendar */
.cw{background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:var(--r);overflow:visible;position:relative;}
.cw-body{overflow:hidden;border-radius:0 0 var(--r) var(--r);}
.cn{width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,.05);border:none;color:rgba(255,255,255,.6);cursor:pointer;font-size:13px;display:flex;align-items:center;justify-content:center;transition:all .15s;}
.cn:hover{background:rgba(255,255,255,.12);color:#fff;}
.cd{aspect-ratio:1;border-radius:7px;font-size:12px;font-weight:600;display:flex;align-items:center;justify-content:center;transition:all .15s;border:none;background:transparent;cursor:pointer;color:rgba(255,255,255,.6);font-family:inherit;}
.cd:hover:not(.past):not(.sel){background:rgba(255,255,255,.08);color:#fff;}
.cd.past{color:rgba(255,255,255,.2);cursor:not-allowed;}
.cd.sel{background:var(--or);color:#fff;box-shadow:0 0 14px var(--or-glow);}
.cd.tod:not(.sel){border:1px solid rgba(249,115,22,.4);color:var(--or);}

/* submit button */
.bs{width:100%;background:var(--or);color:#fff;font-weight:800;font-size:15px;padding:15px;border-radius:var(--rs);border:none;cursor:pointer;font-family:inherit;transition:all .2s;box-shadow:0 4px 24px var(--or-glow);display:flex;align-items:center;justify-content:center;gap:8px;}
.bs:hover:not(:disabled){background:#fb923c;box-shadow:0 6px 32px rgba(249,115,22,.5);transform:translateY(-1px);}
.bs:disabled{opacity:.5;cursor:not-allowed;transform:none;}

/* availability badge */
.av{display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:5px 12px;border-radius:99px;border:1px solid;}

/* two col layout */
.bgrid{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;}
.cleft{position:sticky;top:20px;}
@media(max-width:900px){
    .bgrid{display:flex!important;flex-direction:column!important;gap:12px;}
    .bgrid>div:first-child{order:1;width:100%;}
    .cleft{order:2;position:static!important;top:auto!important;width:100%;}
}

/* summary rows */
.sr{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);font-size:13px;}
.sr:last-child{border:none;}

/* confirm screen */
.confirm-row{display:flex;justify-content:space-between;align-items:center;padding:9px 14px;border-bottom:1px solid rgba(255,255,255,.05);font-size:12px;}
.confirm-row:last-child{border:none;}

@keyframes fadeUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:translateY(0);}}
.fu{animation:fadeUp .35s ease both;}
@keyframes spin{to{transform:rotate(360deg);}}
.spin{animation:spin 1s linear infinite;}
</style>
@endpush

<div x-data="bk()" x-init="init()" dir="rtl" class="min-h-screen pb-32">

    {{-- HERO --}}
    <div class="text-center pt-12 pb-10 px-4">
        <div style="display:inline-block;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--or);border:1px solid rgba(249,115,22,.3);border-radius:99px;padding:5px 16px;margin-bottom:16px;">
            ONX Edge — الحجز الفوري
        </div>
        <h1 class="font-syne font-black text-4xl md:text-6xl text-white mb-4" style="line-height:1.1;">
            احجز <span style="color:var(--or);">مشروعك</span>
        </h1>
        <p style="color:var(--muted);font-size:13px;max-width:360px;margin:0 auto;">
            اختر الخدمة وأكمل بياناتك — نتواصل معك لتأكيد التفاصيل
        </p>
    </div>

    <div style="max-width:1100px;margin:0 auto;padding:0 16px;">

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- شاشة النجاح (step = done)                                 --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div x-show="step==='done'" x-cloak class="fu" style="max-width:480px;margin:0 auto;">
            <div class="gc" style="padding:40px;text-align:center;">
                <div style="width:72px;height:72px;margin:0 auto 20px;border-radius:50%;border:2px solid #4ade80;background:rgba(74,222,128,.08);display:flex;align-items:center;justify-content:center;font-size:2rem;color:#4ade80;">✓</div>
                <div style="font-family:'Syne',sans-serif;font-size:26px;font-weight:900;color:#fff;margin-bottom:6px;">تم استلام حجزك!</div>
                <div style="display:inline-block;border:1px solid rgba(249,115,22,.3);color:var(--or);font-weight:800;padding:4px 16px;border-radius:99px;margin-bottom:16px;font-size:14px;" x-text="'#'+(done.booking_ref||'')"></div>
                <p style="color:var(--muted);font-size:13px;margin-bottom:20px;line-height:1.7;">سنتواصل معك قريباً لتأكيد التفاصيل ومعلومات العربون.</p>
                <template x-if="done.generated_password">
                    <div style="background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--rs);padding:16px;margin-bottom:20px;text-align:right;">
                        <div style="font-size:10px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px;">بيانات دخولك — احتفظ بها</div>
                        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:12px;">
                            <span style="color:var(--muted);">البريد</span>
                            <span style="color:#fff;font-weight:700;" x-text="form.email"></span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:12px;">
                            <span style="color:var(--muted);">كلمة المرور</span>
                            <span style="color:var(--or);font-weight:800;letter-spacing:.1em;" x-text="done.generated_password"></span>
                        </div>
                        <div style="font-size:10px;color:#f87171;text-align:center;margin-top:8px;">تظهر مرة واحدة فقط</div>
                    </div>
                </template>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a :href="done.confirmation_url" class="bs" style="text-decoration:none;">عرض تفاصيل الحجز ←</a>
                    <a href="/" style="display:block;border:1px solid var(--border);color:var(--muted);text-decoration:none;padding:12px;border-radius:var(--rs);font-size:13px;text-align:center;">العودة للرئيسية</a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- شاشة التأكيد (step = confirm) — قبل الإرسال              --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div x-show="step==='confirm'" x-cloak class="fu" style="max-width:540px;margin:0 auto;">
            <div class="gc" style="padding:28px 24px;">

                {{-- العنوان --}}
                <div style="text-align:center;margin-bottom:22px;">
                    <div style="font-size:32px;margin-bottom:8px;">📋</div>
                    <div style="font-size:20px;font-weight:900;color:#fff;margin-bottom:4px;">مراجعة الحجز</div>
                    <div style="font-size:12px;color:var(--muted);">تأكد من التفاصيل قبل الإرسال النهائي</div>
                </div>

                {{-- بيانات العميل --}}
                <div style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:8px;">بياناتك</div>
                <div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:16px;">
                    <div class="confirm-row">
                        <span style="color:var(--muted);">الاسم</span>
                        <span style="color:#fff;font-weight:700;" x-text="form.name"></span>
                    </div>
                    <div class="confirm-row">
                        <span style="color:var(--muted);">الهاتف</span>
                        <span style="color:#fff;font-weight:700;direction:ltr;" x-text="form.phone"></span>
                    </div>
                    <div class="confirm-row">
                        <span style="color:var(--muted);">البريد</span>
                        <span style="color:#fff;font-weight:700;" x-text="form.email"></span>
                    </div>
                </div>

                {{-- تفاصيل الحجز --}}
                <div style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:8px;">تفاصيل الحجز</div>
                <div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:16px;">
                    <div class="confirm-row">
                        <span style="color:var(--muted);">الخدمة</span>
                        <span style="color:#fff;font-weight:700;" x-text="sel?.name"></span>
                    </div>
                    <div class="confirm-row">
                        <span style="color:var(--muted);">الباقة</span>
                        <span style="color:#fff;font-weight:700;" x-text="curPkg?.name||(isCustom?'مخصصة':'—')"></span>
                    </div>
                    <div x-show="date" class="confirm-row">
                        <span style="color:var(--muted);">التاريخ</span>
                        <span style="color:#fff;font-weight:700;" x-text="fd(date)"></span>
                    </div>
                </div>

                {{-- ملخص السعر --}}
                <div style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:8px;">ملخص السعر</div>
                <div style="background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:12px;padding:14px;margin-bottom:16px;">

                    <div x-show="pricing.base > 0" style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:12px;">
                        <span style="color:var(--muted);">السعر الأساسي</span>
                        <span style="color:#fff;font-weight:600;" x-text="n(pricing.base)+' دج'"></span>
                    </div>

                    <div x-show="pricing.options_cost > 0" style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:12px;">
                        <span style="color:var(--muted);">الخيارات</span>
                        <span style="color:var(--or);font-weight:600;" x-text="'+ '+n(pricing.options_cost)+' دج'"></span>
                    </div>

                    <div x-show="pricing.time_cost > 0" style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:12px;">
                        <span style="color:var(--muted);">رسوم الوقت</span>
                        <span style="color:var(--or);font-weight:600;" x-text="'+ '+n(pricing.time_cost)+' دج'"></span>
                    </div>

                    <div x-show="pricing.travel_cost > 0" style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:12px;">
                        <span style="color:var(--muted);">رسوم التنقل</span>
                        <span style="color:var(--or);font-weight:600;" x-text="'+ '+n(pricing.travel_cost)+' دج'"></span>
                    </div>

                    {{-- الخصم --}}
                    <div x-show="promoOk && promoDiscount > 0" style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:12px;">
                        <span style="color:#4ade80;">🎉 خصم الكود (<span x-text="form.promo_code"></span>)</span>
                        <span style="color:#4ade80;font-weight:700;" x-text="'- '+n(promoDiscount)+' دج'"></span>
                    </div>

                    {{-- الإجمالي النهائي --}}
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,.1);">
                        <span style="font-size:14px;font-weight:800;color:#fff;">الإجمالي النهائي</span>
                        <span style="font-size:24px;font-weight:900;line-height:1;"
                              :style="promoOk?'color:#4ade80;':'color:var(--or);'"
                              x-text="n(promoOk && promoFinal >= 0 ? promoFinal : pricing.total)+' دج'">
                        </span>
                    </div>

                    {{-- العربون --}}
                    <div x-show="pricing.deposit > 0"
                         style="margin-top:10px;background:rgba(249,115,22,.08);border:1px solid rgba(249,115,22,.2);border-radius:8px;padding:8px 12px;display:flex;justify-content:space-between;">
                        <span style="font-size:11px;color:rgba(249,115,22,.8);">العربون المطلوب</span>
                        <span style="font-size:14px;font-weight:800;color:var(--or);" x-text="n(pricing.deposit)+' دج'"></span>
                    </div>
                </div>

                {{-- رسالة خطأ --}}
                <div x-show="err" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.3);color:#f87171;padding:10px 14px;border-radius:var(--rs);font-size:12px;margin-bottom:12px;" x-text="err"></div>

                {{-- أزرار --}}
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <button type="button" @click="go()" :disabled="busy" class="bs">
                        <span x-show="!busy">✅ تأكيد وإرسال الحجز</span>
                        <span x-show="busy" style="display:flex;align-items:center;gap:8px;">
                            <svg class="spin" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24">
                                <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                            جاري الإرسال...
                        </span>
                    </button>
                    <button type="button" @click="step='form';err=''"
                        style="border:1px solid var(--border);background:transparent;color:var(--muted);font-size:13px;padding:12px;border-radius:var(--rs);cursor:pointer;font-family:inherit;transition:all .15s;"
                        onmouseover="this.style.borderColor='rgba(249,115,22,.3)';this.style.color='#fff'"
                        onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
                        ← تعديل البيانات
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════ --}}
        {{-- نموذج الحجز (step = form)                                 --}}
        {{-- ══════════════════════════════════════════════════════════ --}}
        <div x-show="step==='form'" x-cloak>

            {{-- اختر الخدمة --}}
            <div style="margin-bottom:24px;">
                <div class="sh"><span>اختر نوع الخدمة</span></div>

                <div x-show="initLoading" style="text-align:center;padding:40px 0;color:var(--dim);font-size:13px;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <svg class="spin" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24">
                        <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    جاري التحميل...
                </div>

                <div x-show="!initLoading">
                    <template x-for="cat in categories" :key="cat.id">
                        <div x-show="(bycat[cat.id]||[]).length > 0" style="margin-bottom:20px;">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                                <span x-text="cat.icon" style="font-size:1rem;"></span>
                                <span style="font-size:11px;font-weight:800;letter-spacing:.07em;text-transform:uppercase;color:var(--or);border:1px solid rgba(249,115,22,.3);border-radius:99px;padding:3px 12px;" x-text="cat.name"></span>
                            </div>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;">
                                <template x-for="svc in (bycat[cat.id]||[])" :key="svc.id">
                                    <button type="button" @click="pickSvc(svc)" class="sc" :class="sel?.id===svc.id?'on':''">
                                        <div x-show="sel?.id===svc.id" style="position:absolute;top:8px;left:8px;width:18px;height:18px;background:var(--or);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9px;color:#fff;">✓</div>
                                        <span x-text="svc.icon||cat.icon" style="font-size:2rem;display:block;margin-bottom:8px;"></span>
                                        <div style="font-weight:700;font-size:13px;color:#fff;margin-bottom:4px;" x-text="svc.name"></div>
                                        <div style="font-size:11px;color:var(--muted);line-height:1.4;" x-text="(svc.description||'').substring(0,55)"></div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- النموذج الكامل --}}
            <div x-show="sel" class="bgrid fu" id="booking-form-start">

                {{-- ── العمود الأيمن ── --}}
                <div style="display:flex;flex-direction:column;gap:16px;">

                    {{-- اختيار الباقة --}}
                    <div class="gc" style="padding:20px;">
                        <div class="sh"><span>الباقة</span></div>

                        <div x-show="loadPkg" style="text-align:center;padding:20px;color:var(--dim);font-size:12px;display:flex;align-items:center;justify-content:center;gap:6px;">
                            <svg class="spin" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24">
                                <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                            جاري التحميل...
                        </div>

                        <div x-show="!loadPkg">
                            <div class="pkg-grid"
                                 :style="`grid-template-columns: repeat(${Math.min(pkgs.filter(p=>!p.is_buildable).length, 3)}, 1fr);`">

                                <template x-for="pkg in pkgs.filter(p=>!p.is_buildable)" :key="pkg.id">
                                    <button type="button" @click="pickPkg(pkg)" class="pc" :class="curPkg?.id===pkg.id&&!isCustom?'on':''">
                                        <div class="pc-line"></div>
                                        <div x-show="pkg.is_featured" class="pc-badge">⭐ الأكثر طلباً</div>
                                        <div style="position:absolute;top:0;left:0;width:0;height:0;border-style:solid;border-width:38px 38px 0 0;border-radius:16px 0 0 0;transition:border-color .25s;pointer-events:none;"
                                            :style="curPkg?.id===pkg.id&&!isCustom?'border-color:var(--or) transparent transparent transparent':'border-color:transparent transparent transparent transparent'"></div>

                                        <div class="pc-icon" x-text="pkg.icon||'📦'"></div>
                                        <div style="font-size:13px;font-weight:700;color:#fff;margin-bottom:14px;" x-text="pkg.name"></div>
                                        <div class="pc-prices">
                                            <div class="pc-old" x-show="pkg.old_price>0&&pkg.price<pkg.old_price" x-text="n(pkg.old_price)+' دج'"></div>
                                            <div class="pc-old" x-show="!(pkg.old_price>0&&pkg.price<pkg.old_price)" style="opacity:0;">—</div>
                                            <div class="pc-price"><span>دج</span><span x-text="pkg.price>0?n(pkg.price):(pkg.price_note||'—')"></span></div>
                                        </div>
                                    </button>
                                </template>
                            </div>

                            {{-- باقة مخصصة --}}
                            <template x-if="pkgs.some(p=>p.is_buildable)">
                                <button type="button" @click="useCustom()" class="pc" :class="isCustom?'on':''"
                                    style="margin-top:12px;border-style:dashed;flex-direction:row;justify-content:space-between;padding:18px 24px;text-align:right;">
                                    <div style="position:absolute;top:0;left:0;width:0;height:0;border-style:solid;border-width:38px 38px 0 0;border-radius:16px 0 0 0;transition:border-color .25s;pointer-events:none;"
                                        :style="isCustom?'border-color:var(--or) transparent transparent transparent':'border-color:transparent transparent transparent transparent'"></div>

                                    <div style="display:flex;align-items:center;gap:14px;">
                                        <div style="font-size:22px;color:var(--or);">✦</div>
                                        <div>
                                            <div style="font-weight:700;font-size:13px;color:#fff;margin-bottom:2px;">باقة مخصصة</div>
                                            <div style="font-size:11px;color:var(--muted);">اختر الخيارات التي تريدها فقط</div>
                                        </div>
                                    </div>
                                    <div style="display:flex;gap:7px;flex-wrap:wrap;justify-content:flex-end;">
                                        <span style="font-size:10px;font-weight:600;border:1px solid rgba(255,255,255,.08);border-radius:99px;padding:3px 10px;" :style="isCustom?'border-color:rgba(249,115,22,.25);color:rgba(249,115,22,.7);':'color:rgba(255,255,255,.35);'">تصوير</span>
                                        <span style="font-size:10px;font-weight:600;border:1px solid rgba(255,255,255,.08);border-radius:99px;padding:3px 10px;" :style="isCustom?'border-color:rgba(249,115,22,.25);color:rgba(249,115,22,.7);':'color:rgba(255,255,255,.35);'">مونتاج</span>
                                        <span style="font-size:10px;font-weight:600;border:1px solid rgba(255,255,255,.08);border-radius:99px;padding:3px 10px;" :style="isCustom?'border-color:rgba(249,115,22,.25);color:rgba(249,115,22,.7);':'color:rgba(255,255,255,.35);'">طباعة</span>
                                        <span style="font-size:10px;font-weight:600;border:1px solid rgba(255,255,255,.08);border-radius:99px;padding:3px 10px;" :style="isCustom?'border-color:rgba(249,115,22,.25);color:rgba(249,115,22,.7);':'color:rgba(255,255,255,.35);'">+ المزيد</span>
                                    </div>
                                </button>
                            </template>
                        </div>

                        {{-- خيارات الباقة المخصصة --}}
                        <div x-show="isCustom&&custOpts.length" style="margin-top:12px;border-top:1px solid var(--border);padding-top:12px;">
                            <div class="fl">اختر الخيارات</div>
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <template x-for="opt in custOpts" :key="opt.id">
                                    <button type="button" @click="togOpt(opt)"
                                        style="display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:var(--rs);border:1px solid;transition:all .15s;background:transparent;cursor:pointer;font-family:inherit;"
                                        :style="opts[opt.id]?'border-color:var(--or);background:var(--or-dim);':'border-color:var(--border);'">
                                        <div style="width:16px;height:16px;border:2px solid;border-radius:4px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"
                                            :style="opts[opt.id]?'border-color:var(--or);background:var(--or);':'border-color:rgba(255,255,255,.2);'">
                                            <span x-show="opts[opt.id]" style="color:#fff;font-size:9px;">✓</span>
                                        </div>
                                        <span style="flex-grow:1;text-align:right;font-size:12px;color:#fff;" x-text="opt.name"></span>
                                        <span style="font-size:11px;font-weight:700;color:var(--or);white-space:nowrap;">+<span x-text="n(opt.price)"></span> دج</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- معلومات العميل --}}
                    <div class="gc" style="padding:20px;">
                        <div class="sh"><span>معلوماتك</span></div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div style="grid-column:1/-1;">
                                <label class="fl">الاسم الكامل *</label>
                                <input type="text" x-model="form.name" class="fi" placeholder="اسمك الكامل">
                            </div>
                            <div>
                                <label class="fl">رقم الهاتف *</label>
                                <input type="tel" x-model="form.phone" class="fi" placeholder="0550000000" dir="ltr">
                            </div>
                            <div>
                                <label class="fl">البريد الإلكتروني *</label>
                                <input type="email" x-model="form.email" class="fi" placeholder="email@example.com" dir="ltr">
                            </div>
                            <div style="grid-column:1/-1;">
                                <label class="fl">ملاحظات <span style="color:var(--dim);font-weight:400;text-transform:none;">(اختياري)</span></label>
                                <textarea x-model="form.notes" class="fi" style="min-height:70px;resize:vertical;" placeholder="أي تفاصيل إضافية..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- مكان الفعالية + الوقت --}}
                    <div class="gc" style="padding:20px;" x-show="(sel?.show_wilaya_selector||sel?.show_venue_selector)||(date&&btype()!=='subscription')">
                        <div class="sh"><span>مكان الفعالية والوقت</span></div>

                        <div x-show="sel?.show_wilaya_selector||sel?.show_venue_selector">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                                <div x-show="sel?.show_wilaya_selector">
                                    <label class="fl">الولاية</label>
                                    <select x-model="form.wilaya_id" @change="debouncedOnWilaya()" class="fi">
                                        <option value="">اختر الولاية...</option>
                                        <template x-for="w in wilayas" :key="w.id">
                                            <option :value="w.id" x-text="w.code+' — '+w.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div x-show="sel?.show_venue_selector">
                                    <label class="fl">القاعة</label>
                                    <div x-show="!showVenueInput">
                                        <select x-model="form.venue_id" @change="form.venue_custom=''" class="fi">
                                            <option value="">اختر القاعة...</option>
                                            <template x-for="v in venues" :key="v.id">
                                                <option :value="v.id" x-text="v.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div x-show="showVenueInput">
                                        <input type="text" x-model="form.venue_custom" @input="form.venue_id=null" class="fi" placeholder="اكتب اسم القاعة...">
                                    </div>
                                </div>
                            </div>

                            <div x-show="pricing.travel_cost>0" style="margin-bottom:10px;display:flex;align-items:center;justify-content:space-between;font-size:11px;background:rgba(249,115,22,.06);border:1px solid rgba(249,115,22,.2);border-radius:8px;padding:6px 12px;">
                                <span style="color:var(--muted);">رسوم التنقل</span>
                                <span style="color:var(--or);font-weight:700;">+<span x-text="n(pricing.travel_cost)"></span> دج</span>
                            </div>

                            <div x-show="sel?.show_venue_selector" style="margin-bottom:10px;">
                                <button x-show="!showVenueInput" type="button"
                                    @click="showVenueInput=true;form.venue_id=null;"
                                    style="font-size:11px;color:var(--muted);background:transparent;border:1px dashed rgba(255,255,255,.15);border-radius:var(--rs);padding:6px 14px;cursor:pointer;font-family:inherit;transition:all .15s;display:block;width:100%;"
                                    onmouseover="this.style.borderColor='rgba(249,115,22,.4)';this.style.color='var(--or)'"
                                    onmouseout="this.style.borderColor='rgba(255,255,255,.15)';this.style.color='var(--muted)'"
                                >+ لم أجد قاعتي — أضف يدوياً</button>
                                <button x-show="showVenueInput" type="button"
                                    @click="showVenueInput=false;form.venue_custom='';"
                                    style="font-size:11px;color:var(--dim);background:transparent;border:none;cursor:pointer;font-family:inherit;padding:0;">
                                    ← رجوع إلى القائمة
                                </button>
                            </div>

                            <div x-show="date&&btype()!=='subscription'" style="border-top:1px solid var(--border);margin-bottom:14px;"></div>
                        </div>

                        <div x-show="date&&btype()!=='subscription'">
                            <div style="font-size:11px;font-weight:700;letter-spacing:.06em;color:var(--muted);margin-bottom:10px;text-transform:uppercase;">الوقت</div>
                            <div x-show="avail==='available'">
                                <template x-if="btype()==='appointment'">
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                        <div>
                                            <label class="fl">وقت الموعد</label>
                                            <input type="time" x-model="form.slot_start" @change="calcEnd()" class="fi" dir="ltr">
                                        </div>
                                        <div>
                                            <label class="fl">ينتهي</label>
                                            <input type="time" :value="form.slot_end" class="fi" style="opacity:.5;" dir="ltr" readonly>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="btype()!=='appointment'">
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                        <div>
                                            <label class="fl">وقت البداية</label>
                                            <input type="time" x-model="form.start_time" @change="fetchPrice()" class="fi" dir="ltr">
                                        </div>
                                        <div>
                                            <label class="fl">وقت النهاية</label>
                                            <input type="time" value="04:00" class="fi" style="opacity:.5;cursor:not-allowed;" dir="ltr" readonly>
                                        </div>
                                    </div>
                                </template>
                                <div style="margin-top:8px;display:flex;align-items:center;justify-content:space-between;font-size:11px;background:rgba(249,115,22,.06);border:1px solid rgba(249,115,22,.2);border-radius:8px;padding:6px 12px;">
                                    <span style="color:var(--muted);">⚠ ما بعد 04:00 صباحاً تُطبَّق رسوم إضافية</span>
                                    <span x-show="pricing.time_cost>0" style="color:var(--or);font-weight:700;">+<span x-text="n(pricing.time_cost)"></span> دج</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- رسالة الخطأ --}}
                    <div x-show="err" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.3);color:#f87171;padding:12px 16px;border-radius:var(--rs);font-size:13px;" x-text="err"></div>

                    {{-- زر الموبايل --}}
                    <div class="md:hidden">
                        <button type="button" @click="goToConfirm()" :disabled="!ok()" class="bs">
                            مراجعة وتأكيد الحجز ←
                        </button>
                    </div>
                </div>

                {{-- ── العمود الأيسر: التقويم + الملخص ── --}}
                <div class="cleft" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- التقويم --}}
                    <div x-show="btype()!=='subscription'" class="cw">
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border-bottom:1px solid var(--border);gap:8px;">

                            {{-- زر الشهر التالي --}}
                            <button type="button" @click="calNext()" title="الشهر التالي"
                                style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);color:rgba(255,255,255,.6);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;"
                                onmouseover="this.style.background='rgba(249,115,22,.15)';this.style.borderColor='rgba(249,115,22,.4)';this.style.color='#f97316'"
                                onmouseout="this.style.background='rgba(255,255,255,.05)';this.style.borderColor='rgba(255,255,255,.08)';this.style.color='rgba(255,255,255,.6)'">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M9 3L5 7l4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>

                            {{-- العنوان: الشهر + السنة --}}
                            <div style="display:flex;align-items:center;gap:6px;flex:1;justify-content:center;">
                                {{-- منتقي الشهر --}}
                                <div style="position:relative;">
                                    <button type="button" @click="showMonthPicker=!showMonthPicker;showYearPicker=false"
                                        style="font-size:13px;font-weight:700;color:#fff;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:7px;padding:4px 10px;cursor:pointer;font-family:inherit;transition:all .15s;"
                                        onmouseover="this.style.borderColor='rgba(249,115,22,.4)';this.style.color='#f97316'"
                                        onmouseout="this.style.borderColor='rgba(255,255,255,.08)';this.style.color='#fff'"
                                        x-text="monthName(calM)">
                                    </button>
                                    <div x-show="showMonthPicker" x-cloak @click.outside="showMonthPicker=false"
                                        style="position:absolute;top:calc(100% + 8px);right:-60px;z-index:500;">
                                        <div style="background:#1a1a1a;border:1px solid rgba(255,255,255,.14);border-radius:14px;padding:8px;box-shadow:0 16px 48px rgba(0,0,0,.95);display:grid;grid-template-columns:repeat(3,1fr);gap:4px;width:240px;">
                                            <template x-for="(mname, midx) in monthNames" :key="midx+1">
                                                <button type="button"
                                                    @click="calM=midx+1;showMonthPicker=false;clearDateIfPast()"
                                                    style="font-size:12px;font-weight:600;padding:8px 4px;border-radius:8px;border:none;cursor:pointer;font-family:inherit;text-align:center;white-space:nowrap;transition:all .15s;"
                                                    :style="calM===midx+1?'background:var(--or);color:#fff;':'background:rgba(255,255,255,.06);color:rgba(255,255,255,.8);'"
                                                    @mouseover="if(calM!==midx+1)$el.style.background='rgba(255,255,255,.14)'"
                                                    @mouseout="if(calM!==midx+1)$el.style.background='rgba(255,255,255,.06)'"
                                                    x-text="mname">
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- منتقي السنة --}}
                                <div style="position:relative;">
                                    <button type="button" @click="showYearPicker=!showYearPicker;showMonthPicker=false"
                                        style="font-size:13px;font-weight:700;color:#fff;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:7px;padding:4px 10px;cursor:pointer;font-family:inherit;transition:all .15s;"
                                        onmouseover="this.style.borderColor='rgba(249,115,22,.4)';this.style.color='#f97316'"
                                        onmouseout="this.style.borderColor='rgba(255,255,255,.08)';this.style.color='#fff'"
                                        x-text="calY">
                                    </button>
                                    <div x-show="showYearPicker" x-cloak @click.outside="showYearPicker=false"
                                        style="position:absolute;top:calc(100% + 8px);left:0;z-index:500;">
                                        <div style="background:#1a1a1a;border:1px solid rgba(255,255,255,.14);border-radius:14px;padding:8px;box-shadow:0 16px 48px rgba(0,0,0,.95);display:grid;grid-template-columns:repeat(2,1fr);gap:4px;width:160px;">
                                            <template x-for="yr in yearRange" :key="yr">
                                                <button type="button"
                                                    @click="calY=yr;showYearPicker=false;clearDateIfPast()"
                                                    style="font-size:13px;font-weight:700;padding:8px 4px;border-radius:8px;border:none;cursor:pointer;font-family:inherit;text-align:center;white-space:nowrap;transition:all .15s;"
                                                    :style="calY===yr?'background:var(--or);color:#fff;':'background:rgba(255,255,255,.06);color:rgba(255,255,255,.8);'"
                                                    @mouseover="if(calY!==yr)$el.style.background='rgba(255,255,255,.12)'"
                                                    @mouseout="if(calY!==yr)$el.style.background='rgba(255,255,255,.06)'"
                                                    x-text="yr">
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- زر الشهر السابق --}}
                            <button type="button" @click="calPrev()" title="الشهر السابق"
                                style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);color:rgba(255,255,255,.6);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;"
                                :disabled="calY===todayYear&&calM===todayMonth"
                                :style="(calY===todayYear&&calM===todayMonth)?'opacity:.3;cursor:not-allowed;':''"
                                onmouseover="if(!(this.disabled))this.style.background='rgba(249,115,22,.15)';if(!(this.disabled))this.style.borderColor='rgba(249,115,22,.4)';if(!(this.disabled))this.style.color='#f97316'"
                                onmouseout="this.style.background='rgba(255,255,255,.05)';this.style.borderColor='rgba(255,255,255,.08)';this.style.color='rgba(255,255,255,.6)'">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M5 3l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>

                        <div class="cw-body">
                            {{-- أسماء الأيام --}}
                            <div style="display:grid;grid-template-columns:repeat(7,1fr);border-bottom:1px solid var(--border);">
                                <template x-for="d in ['ح','ن','ث','ر','خ','ج','س']" :key="d">
                                    <div style="padding:8px 0;text-align:center;font-size:10px;color:var(--dim);font-weight:700;" x-text="d"></div>
                                </template>
                            </div>

                            {{-- شبكة الأيام --}}
                            <div style="display:grid;grid-template-columns:repeat(7,1fr);padding:8px;gap:3px;">
                                <template x-for="i in calGrid.offset" :key="'e'+i"><div></div></template>
                                <template x-for="day in calGrid.days" :key="day.s">
                                    <button type="button" class="cd"
                                        :class="{past:day.p,sel:date===day.s,tod:day.t}"
                                        :disabled="day.p"
                                        @click="!day.p&&pickDate(day.s)"
                                        x-text="day.d">
                                    </button>
                                </template>
                            </div>

                            <div x-show="date" style="padding:10px 12px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                                <span style="font-size:11px;color:var(--muted);">📅 <span x-text="fd(date)"></span></span>
                                <span x-show="avail" class="av"
                                    :style="avail==='available'?'border-color:rgba(34,197,94,.3);color:#4ade80;background:rgba(34,197,94,.08);':avail==='pending'?'border-color:rgba(234,179,8,.3);color:#facc15;background:rgba(234,179,8,.08);':'border-color:rgba(239,68,68,.3);color:#f87171;background:rgba(239,68,68,.08);'"
                                    x-text="avail==='available'?'✓ متاح':avail==='pending'?'⏳ قيد المراجعة':'✗ محجوز'">
                                </span>
                                <div x-show="!avail&&date" style="font-size:10px;color:var(--dim);display:flex;align-items:center;gap:4px;">
                                    <svg class="spin" style="width:10px;height:10px;" fill="none" viewBox="0 0 24 24">
                                        <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                    </svg>
                                    جاري التحقق...
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ملخص الحجز --}}
                    <div class="gc" style="padding:20px;">
                        <div class="sh"><span>ملخص الحجز</span></div>

                        <div x-show="!curPkg&&!isCustom" style="text-align:center;padding:20px 0;color:var(--dim);font-size:12px;">
                            اختر باقة لعرض الملخص
                        </div>

                        <div x-show="curPkg||isCustom" style="display:flex;flex-direction:column;">
                            <div class="sr" x-show="sel">
                                <span style="color:var(--muted);">الخدمة</span>
                                <span style="font-weight:700;color:#fff;font-size:13px;" x-text="sel?.name"></span>
                            </div>
                            <div class="sr" x-show="curPkg">
                                <span style="color:var(--muted);">الباقة</span>
                                <span style="font-weight:700;color:#fff;font-size:13px;" x-text="curPkg?.name||'مخصصة'"></span>
                            </div>
                            <div class="sr" x-show="date">
                                <span style="color:var(--muted);">التاريخ</span>
                                <span style="font-weight:700;color:#fff;font-size:13px;" x-text="fd(date)"></span>
                            </div>
                            <div class="sr" x-show="pricing.base>0">
                                <span style="color:var(--muted);">السعر الأساسي</span>
                                <span style="font-weight:700;color:#fff;font-size:13px;" x-text="n(pricing.base)+' دج'"></span>
                            </div>
                            <div class="sr" x-show="pricing.options_cost>0">
                                <span style="color:var(--muted);">الخيارات</span>
                                <span style="font-weight:700;color:var(--or);font-size:13px;" x-text="'+'+n(pricing.options_cost)+' دج'"></span>
                            </div>
                            <div class="sr" x-show="pricing.time_cost>0">
                                <span style="color:var(--muted);">رسوم الوقت</span>
                                <span style="font-weight:700;color:var(--or);font-size:13px;" x-text="'+'+n(pricing.time_cost)+' دج'"></span>
                            </div>
                            <div class="sr" x-show="pricing.travel_cost>0">
                                <span style="color:var(--muted);">رسوم التنقل</span>
                                <span style="font-weight:700;color:var(--or);font-size:13px;" x-text="'+'+n(pricing.travel_cost)+' دج'"></span>
                            </div>

                            {{-- ══ الإجمالي قبل الخصم ══ --}}
                            <div x-show="pricing.total>0" style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
                                <span style="font-size:13px;font-weight:700;color:#fff;">الإجمالي</span>
                                <span style="font-size:22px;font-weight:900;color:var(--or);font-family:'Syne',sans-serif;" x-text="n(pricing.total)+' دج'"></span>
                            </div>

                            {{-- ══ العربون ══ --}}
                            <div x-show="pricing.deposit>0" style="margin-top:10px;background:rgba(249,115,22,.08);border:1px solid rgba(249,115,22,.25);border-radius:var(--rs);padding:10px 12px;display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:11px;color:rgba(249,115,22,.8);">العربون المطلوب</span>
                                <span style="font-size:15px;font-weight:800;color:var(--or);" x-text="n(pricing.deposit)+' دج'"></span>
                            </div>

                            {{-- ══════════════════════════════════════════════════════ --}}
                            {{-- قسم الرمز الترويجي                                    --}}
                            {{-- ══════════════════════════════════════════════════════ --}}
                            <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border);">
                                <div style="font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px;">🎟 رمز ترويجي</div>

                                <div style="display:flex;gap:8px;">
                                    <input type="text" x-model="form.promo_code"
                                           :disabled="promoOk"
                                           class="fi" placeholder="PROMO2026" dir="ltr"
                                           style="flex:1;padding:8px 12px;font-size:12px;text-transform:uppercase;"
                                           @keyup.enter="applyPromo()">
                                    <button type="button"
                                        @click="promoOk ? resetPromo() : applyPromo()"
                                        :disabled="promoLoading"
                                        style="background:rgba(255,255,255,.06);border:1px solid var(--border);color:#fff;font-weight:700;font-size:12px;padding:0 14px;border-radius:var(--rs);cursor:pointer;white-space:nowrap;font-family:inherit;transition:all .15s;min-width:70px;display:flex;align-items:center;justify-content:center;gap:4px;"
                                        :style="promoOk ? 'border-color:rgba(239,68,68,.3);color:#f87171;' : promoLoading ? 'opacity:.5;cursor:not-allowed;' : ''">
                                        <svg x-show="promoLoading" class="spin" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24">
                                            <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                        </svg>
                                        <span x-show="!promoLoading" x-text="promoOk ? '✕ إلغاء' : 'تطبيق'"></span>
                                    </button>
                                </div>

                                {{-- رسالة الكود --}}
                                <div x-show="promoMsg" style="margin-top:6px;font-size:11px;padding:5px 8px;border-radius:6px;"
                                     :style="promoOk ? 'color:#4ade80;background:rgba(74,222,128,.07);' : 'color:#f87171;background:rgba(239,68,68,.07);'"
                                     x-text="promoMsg">
                                </div>

                                {{-- عرض الخصم --}}
                                <div x-show="promoOk && promoDiscount > 0"
                                     style="margin-top:8px;display:flex;justify-content:space-between;align-items:center;background:rgba(74,222,128,.07);border:1px solid rgba(74,222,128,.2);border-radius:var(--rs);padding:8px 12px;">
                                    <span style="font-size:11px;color:#4ade80;font-weight:700;">🎉 خصم مطبّق</span>
                                    <span style="font-size:13px;font-weight:800;color:#4ade80;" x-text="'- '+n(promoDiscount)+' دج'"></span>
                                </div>

                                {{-- السعر بعد الخصم --}}
                                <div x-show="promoOk && promoFinal >= 0"
                                     style="margin-top:6px;display:flex;justify-content:space-between;align-items:center;border-top:1px dashed rgba(255,255,255,.08);padding-top:10px;">
                                    <span style="font-size:13px;font-weight:800;color:#fff;">السعر بعد الخصم</span>
                                    <span style="font-size:22px;font-weight:900;color:#4ade80;" x-text="n(promoFinal)+' دج'"></span>
                                </div>
                            </div>
                            {{-- ══════════════════════════════════════════════════════ --}}

                            {{-- زر المراجعة قبل التأكيد (ديسكتوب) --}}
                            <button type="button" @click="goToConfirm()" :disabled="!ok()" class="bs hidden md:flex" style="margin-top:16px;">
                                مراجعة وتأكيد الحجز ←
                            </button>

                            <p x-show="!ok()&&(curPkg||isCustom)" style="font-size:10px;color:var(--dim);text-align:center;margin-top:8px;">
                                أكمل جميع البيانات المطلوبة للمتابعة
                            </p>
                        </div>
                    </div>

                </div>{{-- /cleft --}}
            </div>{{-- /bgrid --}}
        </div>{{-- /step=form --}}
    </div>
</div>

@push('scripts')
<script>
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

function bk() {
    return {
        initLoading: true,
        step: 'form',       // 'form' | 'confirm' | 'done'
        categories: [],
        bycat: {},
        wilayas: [],
        venues: [],
        pkgs: [],
        custOpts: [],
        sel: null,
        curPkg: null,
        isCustom: false,
        opts: {},
        date: null,
        avail: null,
        loadPkg: false,
        showVenueInput: false,
        showMonthPicker: false,
        showYearPicker: false,

        form: {
            name: '', email: '', phone: '', notes: '',
            start_time: '19:00', end_time: '04:00',
            slot_start: '', slot_end: '',
            wilaya_id: null, venue_id: null, venue_custom: '', promo_code: ''
        },

        pricing: { base: 0, options_cost: 0, time_cost: 0, travel_cost: 0, subtotal: 0, total: 0, deposit: 0 },

        // ── الكود الترويجي ──
        promoOk: false,
        promoMsg: '',
        promoDiscount: 0,
        promoFinal: 0,
        promoLoading: false,

        busy: false,
        err: '',
        done: {},
        lastSubmit: 0,

        todayYear: new Date().getFullYear(),
        todayMonth: new Date().getMonth() + 1,
        calY: new Date().getFullYear(),
        calM: new Date().getMonth() + 1,

        monthNames: ['جانفي','فيفري','مارس','أفريل','ماي','جوان','جويلية','اوت','سبتمبر','أكتوبر','نوفمبر','ديسمبر'],
        yearRange: Array.from({length: 5}, (_, i) => new Date().getFullYear() + i),

        get calGrid() {
            const y = this.calY, m = this.calM;
            const firstDayOfWeek = new Date(y, m - 1, 1).getDay();
            const offsetArr = Array.from({length: firstDayOfWeek}, (_, i) => i);
            const daysInMonth = new Date(y, m, 0).getDate();
            const today = new Date(); today.setHours(0,0,0,0);
            const daysArr = Array.from({length: daysInMonth}, (_, i) => {
                const d = i + 1;
                const dt = new Date(y, m - 1, d);
                const s = `${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                return { d, s, p: dt < today, t: dt.getTime() === today.getTime() };
            });
            return { offset: offsetArr, days: daysArr };
        },

        btype() { return this.sel?.booking_type ?? 'event'; },
        monthName(m) { return this.monthNames[m - 1] || ''; },

        clearDateIfPast() {
            if (!this.date) return;
            const today = new Date(); today.setHours(0,0,0,0);
            const selected = new Date(this.date + 'T00:00:00');
            if (selected < today) { this.date = null; this.avail = null; }
        },

        async init() {
            try {
                const cached = localStorage.getItem('smartBookingInit');
                if (cached) {
                    const data = JSON.parse(cached);
                    if (Date.now() - data.timestamp < 3600000) {
                        this.categories = data.categories;
                        this.wilayas    = data.wilayas;
                        this.bycat      = data.bycat;
                        this.initLoading = false;
                        return;
                    }
                }
                const r = await fetch('/api/smart-booking/init');
                const d = await r.json();
                this.categories = d.categories;
                this.wilayas    = d.wilayas;

                const promises = d.categories.map(async c => {
                    try {
                        const sr = await fetch('/api/smart-booking/services?category_id=' + c.id);
                        const svcs = await sr.json();
                        return { id: c.id, svcs };
                    } catch (e) { return { id: c.id, svcs: [] }; }
                });
                const results = await Promise.allSettled(promises);
                results.forEach(result => {
                    if (result.status === 'fulfilled') {
                        this.bycat = { ...this.bycat, [result.value.id]: result.value.svcs };
                    }
                });
                localStorage.setItem('smartBookingInit', JSON.stringify({
                    categories: this.categories, wilayas: this.wilayas,
                    bycat: this.bycat, timestamp: Date.now()
                }));
            } catch (e) { this.err = 'فشل في تحميل البيانات. حاول مرة أخرى.'; }
            this.initLoading = false;
        },

        debouncedOnWilaya: debounce(function() {
            this.fetchPrice();
            if (this.sel?.show_venue_selector) {
                fetch('/api/smart-booking/venues?wilaya_id=' + this.form.wilaya_id)
                    .then(r => r.json()).then(d => { this.venues = d; })
                    .catch(e => console.error(e));
            }
        }, 300),

        async pickSvc(svc) {
            this.sel = svc;
            this.curPkg = null; this.isCustom = false; this.opts = {};
            this.date = null; this.avail = null; this.err = ''; this.showVenueInput = false;
            this.pricing = { base:0, options_cost:0, time_cost:0, travel_cost:0, subtotal:0, total:0, deposit: svc.deposit_amount??0 };
            this.resetPromo();
            this.loadPkg = true;
            try {
                const r = await fetch('/api/smart-booking/packages?service_id=' + svc.id);
                this.pkgs = await r.json();
            } catch (e) { this.pkgs = []; }
            this.loadPkg = false;
            if (svc.show_venue_selector) {
                try { const vr = await fetch('/api/smart-booking/venues'); this.venues = await vr.json(); } catch(e){}
            }
            setTimeout(() => {
                document.getElementById('booking-form-start')?.scrollIntoView({ behavior:'smooth', block:'start' });
            }, 100);
        },

        pickPkg(pkg) {
            this.curPkg = pkg; this.isCustom = false; this.opts = {};
            this.resetPromo();
            this.fetchPrice();
        },

        useCustom() {
            this.isCustom = true;
            this.curPkg = this.pkgs.find(p => p.is_buildable) || null;
            this.opts = {};
            this.custOpts = this.curPkg?.active_options ?? [];
            this.resetPromo();
            this.fetchPrice();
        },

        togOpt(opt) {
            if (this.opts[opt.id]) delete this.opts[opt.id]; else this.opts[opt.id] = 1;
            this.opts = { ...this.opts };
            this.resetPromo();
            this.fetchPrice();
        },

        async pickDate(s) {
            this.date = s; this.avail = null;
            try {
                const r = await fetch('/api/smart-booking/availability?date=' + s + '&service_id=' + (this.sel?.id||''));
                const d = await r.json(); this.avail = d.status;
            } catch (e) { this.avail = 'error'; }
        },

        calcEnd() {
            if (!this.form.slot_start || !this.curPkg?.duration) return;
            const [h, m] = this.form.slot_start.split(':').map(Number);
            const e = new Date(2000, 0, 1, h, m + (this.curPkg.duration || 60));
            this.form.slot_end = e.toTimeString().slice(0, 5);
        },

        async fetchPrice() {
            if (!this.sel) return;
            try {
                const r = await fetch('/api/smart-booking/price', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({
                        service_id: this.sel.id, package_id: this.curPkg?.id??null,
                        options: this.opts, start_time: this.form.start_time||null,
                        end_time: '04:00', venue_id: this.form.venue_id??null, wilaya_id: this.form.wilaya_id??null
                    })
                });
                this.pricing = await r.json();
                // إعادة تطبيق الخصم إذا كان الكود مفعّلاً
                if (this.promoOk && this.pricing.total > 0) {
                    this.promoDiscount = Math.min(this.promoDiscount, this.pricing.total);
                    this.promoFinal = Math.max(0, this.pricing.total - this.promoDiscount);
                }
            } catch (e) {}
        },

        // ── تطبيق الكود الترويجي ──────────────────────────────────
        async applyPromo() {
            const code = this.form.promo_code.trim().toUpperCase();
            if (!code) { this.promoMsg = 'أدخل الكود أولاً.'; this.promoOk = false; return; }

            const total = this.pricing.total || 0;
            this.promoLoading = true;
            this.promoMsg = '';
            this.promoOk  = false;
            this.promoDiscount = 0;

            try {
                const r = await fetch('/api/smart-booking/promo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ code, total })
                });
                const d = await r.json();

                if (d.valid) {
                    this.promoOk       = true;
                    this.promoDiscount = d.discount ?? 0;
                    this.promoFinal    = d.final ?? Math.max(0, total - this.promoDiscount);
                    this.promoMsg      = d.message ?? '✓ تم تطبيق الخصم';
                    // تحديث الكود بالأحرف الكبيرة
                    this.form.promo_code = code;
                } else {
                    this.promoMsg = d.message ?? 'الكود غير صالح.';
                    this.promoOk  = false;
                }
            } catch(e) {
                this.promoMsg = 'فشل التحقق. حاول مرة أخرى.';
            }
            this.promoLoading = false;
        },

        // إلغاء الكود الترويجي
        resetPromo() {
            this.promoOk       = false;
            this.promoMsg      = '';
            this.promoDiscount = 0;
            this.promoFinal    = 0;
            this.form.promo_code = '';
        },

        // ── التحقق قبل الإرسال ───────────────────────────────────
        ok() {
            const phoneRegex = /^0[5-7]\d{8}$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return !!(this.sel && (this.curPkg||this.isCustom) && this.form.name.trim() && emailRegex.test(this.form.email) && phoneRegex.test(this.form.phone));
        },

        // ── الانتقال لشاشة المراجعة ──────────────────────────────
        goToConfirm() {
            if (!this.ok()) return;
            this.err  = '';
            this.step = 'confirm';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        // ── الإرسال الفعلي ───────────────────────────────────────
        async go() {
            const now = Date.now();
            if (now - this.lastSubmit < 5000) { this.err = 'يرجى الانتظار قبل الإرسال مرة أخرى.'; return; }
            this.lastSubmit = now; this.busy = true; this.err = '';

            try {
                const payload = {
                    name: this.form.name, email: this.form.email, phone: this.form.phone, notes: this.form.notes,
                    promo_code: this.promoOk ? this.form.promo_code : null,
                    service_id: this.sel.id, type: this.btype(),
                    package_id: this.curPkg?.id??null, package_name: this.curPkg?.name??(this.isCustom?'مخصصة':null),
                    package_snapshot: this.curPkg?{name:this.curPkg.name,price:this.curPkg.price}:null,
                    selected_options: this.opts, event_date: this.date, appointment_date: this.date,
                    start_time: this.form.start_time||null, end_time: '04:00',
                    slot_start: this.form.slot_start||null, slot_end: this.form.slot_end||null,
                    venue_id: this.form.venue_id??null, venue_custom: this.form.venue_custom||null, wilaya_id: this.form.wilaya_id??null
                };

                const r = await fetch('/api/smart-booking/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify(payload)
                });
                const d = await r.json();

                if (d.success) {
                    this.done = d;
                    this.step = 'done';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    this.err  = d.error || Object.values(d.errors||{}).flat().join(' — ');
                    this.step = 'confirm'; // نبقى في شاشة التأكيد مع إظهار الخطأ
                }
            } catch (e) {
                this.err  = 'فشل في الإرسال. حاول مرة أخرى.';
                this.step = 'confirm';
            }
            this.busy = false;
        },

        calNext() {
            if (this.calM === 12) { this.calM = 1; this.calY++; }
            else { this.calM++; }
        },

        calPrev() {
            if (this.calY === this.todayYear && this.calM === this.todayMonth) return;
            if (this.calM === 1) { this.calM = 12; this.calY--; }
            else { this.calM--; }
        },

        n(v) { return Number(v||0).toLocaleString('ar-DZ'); },
        fd(s) { if(!s)return''; return new Date(s+'T00:00:00').toLocaleDateString('ar-DZ',{day:'numeric',month:'long',year:'numeric'}); }
    };
}
</script>
@endpush

@endsection