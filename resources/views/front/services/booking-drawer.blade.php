<!-- ════════════════════════════════════════════════════════════════════
     BOOKING DRAWER COMPONENT — الحجز السريع بـ 3 خطوات
     ════════════════════════════════════════════════════════════════════ -->

<style>
.qb-backdrop{position:fixed;inset:0;z-index:60;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);}
.qb-drawer{position:fixed;top:0;left:0;bottom:0;z-index:61;width:420px;max-width:100vw;background:#0d0d0d;border-right:1px solid rgba(var(--onx-brand-rgb),.2);box-shadow:4px 0 40px rgba(0,0,0,.6);display:flex;flex-direction:column;transform:translateX(-100%);transition:transform .38s cubic-bezier(.4,0,.2,1);}
.qb-drawer.open{transform:translateX(0);}

/* Progress */
.qb-progress{display:flex;gap:5px;padding:12px 16px 0;flex-shrink:0;}
.qb-step-wrap{flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;}
.qb-step-bar{width:100%;height:3px;background:rgba(255,255,255,.1);border-radius:2px;transition:all .3s;}
.qb-step-bar.done,.qb-step-bar.active{background:var(--onx-brand);}
.qb-step-bar.active{box-shadow:0 0 6px rgba(var(--onx-brand-rgb),.5);}
.qb-step-lbl{font-size:9px;font-weight:700;color:rgba(255,255,255,.25);transition:.3s;white-space:nowrap;}
.qb-step-lbl.active,.qb-step-lbl.done{color:rgba(var(--onx-brand-rgb),.8);}

/* Header */
.qb-head{padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0;}
.qb-close-btn{width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,.08);border:none;color:rgba(255,255,255,.5);font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.2s;}
.qb-close-btn:hover{background:rgba(255,255,255,.15);color:#fff;}
.qb-back-btn{background:transparent;border:none;color:rgba(255,255,255,.4);cursor:pointer;padding:2px 4px;font-size:14px;transition:.2s;}
.qb-back-btn:hover{color:#fff;}

/* Hide scrollbar */
.qb-screen::-webkit-scrollbar{display:none;}
.qb-screen{-ms-overflow-style:none;scrollbar-width:none;}

/* Screens */
.qb-screens{position:relative;flex:1;overflow:hidden;}
.qb-screen{position:absolute;inset:0;opacity:0;pointer-events:none;transition:all .32s cubic-bezier(.4,0,.2,1);transform:translateX(60px);overflow-y:auto;padding:16px;}
.qb-screen.active{opacity:1;pointer-events:auto;transform:translateX(0);}
.qb-screen.prev{transform:translateX(-60px);}

/* Fields */
.qb-field{margin-bottom:12px;}
.qb-label{font-size:10px;font-weight:800;color:rgba(255,255,255,.55);margin-bottom:5px;display:block;text-transform:uppercase;letter-spacing:.06em;}
.qb-input{width:100%;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:10px 13px;font-size:13px;color:#fff;outline:none;font-family:inherit;transition:.2s;box-sizing:border-box;}
.qb-input:focus{border-color:rgba(var(--onx-brand-rgb),.5);box-shadow:0 0 0 3px rgba(var(--onx-brand-rgb),.1);}
.qb-input::placeholder{color:rgba(255,255,255,.2);}
.qb-select{width:100%;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:10px 13px;font-size:13px;color:#fff;outline:none;font-family:inherit;transition:.2s;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='rgba(255,255,255,.4)' d='M1 4l5 5 5-5'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:left 12px center;padding-left:34px;box-sizing:border-box;}
.qb-select:focus{border-color:rgba(var(--onx-brand-rgb),.5);}
.qb-select option{background:#1a1a1a;color:#fff;}

/* Pkg box */
.qb-pkg-box{background:rgba(var(--onx-brand-rgb),.07);border:1px solid rgba(var(--onx-brand-rgb),.22);border-radius:11px;padding:10px 13px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;}
.qb-pkg-nm{font-size:12px;font-weight:800;color:#fff;}
.qb-pkg-pr{font-size:14px;font-weight:900;color:var(--onx-brand);}

/* Availability */
.qb-avail{display:inline-flex;align-items:center;gap:5px;font-size:10px;font-weight:700;padding:3px 9px;border-radius:6px;margin-top:5px;}
.qb-avail.available{background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.3);color:#4ade80;}
.qb-avail.booked{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);color:#f87171;}

/* Price bar (always visible at bottom of screens) */
.qb-price-bar{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:12px 14px;margin-top:14px;}
.qb-price-row{display:flex;justify-content:space-between;align-items:center;font-size:11px;padding:3px 0;}
.qb-price-row-label{color:rgba(255,255,255,.4);}
.qb-price-row-val{color:rgba(255,255,255,.75);font-weight:700;}
.qb-price-row-val.extra{color:#fb923c;}
.qb-price-divider{border:none;border-top:1px solid rgba(255,255,255,.07);margin:7px 0;}
.qb-price-total-row{display:flex;justify-content:space-between;align-items:center;}
.qb-price-total-label{font-size:11px;font-weight:700;color:rgba(255,255,255,.5);}
.qb-price-total-val{font-size:17px;font-weight:900;color:var(--onx-brand);}
.qb-price-old{font-size:11px;color:rgba(255,255,255,.28);text-decoration:line-through;margin-left:6px;}

/* Summary */
.qb-sum-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:12px;}
.qb-sum-label{color:rgba(255,255,255,.45);}
.qb-sum-val{color:#fff;font-weight:700;text-align:left;}

/* Promo */
.qb-promo{margin-top:12px;padding:11px 13px;background:rgba(var(--onx-brand-rgb),.05);border:1px solid rgba(var(--onx-brand-rgb),.14);border-radius:10px;}
.qb-promo-lbl{font-size:9px;font-weight:800;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:7px;letter-spacing:.06em;}
.qb-promo-row{display:flex;gap:6px;}
.qb-promo-input{flex:1;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:8px 11px;font-size:12px;color:#fff;font-family:inherit;text-transform:uppercase;letter-spacing:.08em;box-sizing:border-box;outline:none;}
.qb-promo-input:focus{border-color:rgba(var(--onx-brand-rgb),.4);}
.qb-promo-btn{background:rgba(var(--onx-brand-rgb),.15);border:1px solid rgba(var(--onx-brand-rgb),.3);border-radius:8px;padding:8px 13px;font-size:11px;font-weight:800;color:var(--onx-brand);cursor:pointer;transition:.2s;white-space:nowrap;font-family:inherit;}
.qb-promo-btn:hover{background:rgba(var(--onx-brand-rgb),.25);}
.qb-promo-msg{margin-top:5px;font-size:10px;padding:4px 8px;border-radius:6px;}
.qb-promo-msg.ok{background:rgba(34,197,94,.1);color:#4ade80;border:1px solid rgba(34,197,94,.2);}
.qb-promo-msg.err{background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2);}

/* Footer */
.qb-foot{padding:12px 16px;border-top:1px solid rgba(255,255,255,.07);flex-shrink:0;display:flex;gap:8px;}
.qb-btn-prev{flex:1;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.65);border-radius:10px;padding:11px;font-size:12px;font-weight:700;cursor:pointer;font-family:inherit;transition:.2s;}
.qb-btn-prev:hover{background:rgba(255,255,255,.1);}
.qb-btn-next{flex:1;background:var(--onx-brand);border:none;color:#000;border-radius:10px;padding:11px;font-size:12px;font-weight:900;cursor:pointer;font-family:inherit;transition:.2s;box-shadow:0 3px 14px rgba(var(--onx-brand-rgb),.25);}
.qb-btn-next:hover{filter:brightness(1.08);}
.qb-btn-next:disabled{opacity:.45;cursor:not-allowed;}

/* Success */
.qb-success-screen{text-align:center;padding:36px 20px;}
.qb-success-icon{width:60px;height:60px;margin:0 auto 14px;border-radius:50%;background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.3);display:flex;align-items:center;justify-content:center;font-size:26px;color:#4ade80;}
.qb-success-ref{display:inline-block;background:rgba(var(--onx-brand-rgb),.1);border:1px solid rgba(var(--onx-brand-rgb),.25);color:var(--onx-brand);font-weight:900;font-size:14px;padding:5px 15px;border-radius:99px;margin:8px 0;}
.qb-success-btns a{display:block;text-align:center;padding:10px;font-size:12px;font-weight:700;border-radius:10px;text-decoration:none;transition:.2s;margin-top:8px;}
.qb-success-btns .btn-home{background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.1);}

/* hint text */
.qb-hint{font-size:10px;color:rgba(255,255,255,.3);margin-top:4px;}

@media(max-width:767px){.qb-drawer,.qb-backdrop{display:none!important;}}
</style>

<!-- Backdrop -->
<div x-show="drawerOpen" x-cloak class="qb-backdrop" @click="closeDrawer()"></div>

<!-- الـ Drawer -->
<div class="qb-drawer" :class="drawerOpen?'open':''">

    <!-- Progress Bar -->
    <div class="qb-progress">
        <div class="qb-step-wrap">
            <div class="qb-step-bar" :class="qbStep>=1?'done':''"></div>
            <div class="qb-step-lbl" :class="qbStep>=1?'done':''">معلوماتك</div>
        </div>
        <div class="qb-step-wrap">
            <div class="qb-step-bar" :class="qbStep===2?'active':qbStep>2?'done':''"></div>
            <div class="qb-step-lbl" :class="qbStep===2?'active':qbStep>2?'done':''">الفعالية</div>
        </div>
        <div class="qb-step-wrap">
            <div class="qb-step-bar" :class="qbStep===3?'active':qbStep>3?'done':''"></div>
            <div class="qb-step-lbl" :class="qbStep===3?'active':qbStep>3?'done':''">ملخص</div>
        </div>
    </div>

   <!-- Header -->
<div class="qb-head">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-direction: row-reverse;">
        <!-- زر الإغلاق -->
        <button type="button" class="qb-close-btn" @click="closeDrawer()">✕</button>
        
        <!-- النصوص (العنوان) -->
        <div style="text-align: right;">
            <div style="font-size: 10px; color: rgba(255,255,255,.3); font-weight: 700; letter-spacing: .05em; margin-bottom: 3px;">حجز سريع</div>
            <div style="font-size: 16px; font-weight: 900; color: #fff;" x-text="qbStep===1?'معلوماتك':qbStep===2?'الفعالية':qbStep===3?'ملخص الحجز':'تم الحجز ✓'"></div>
        </div>
    </div>
</div>

    <!-- Screens -->
    <div class="qb-screens">

        <!-- ── الشاشة 1: معلومات العميل ── -->
        <div class="qb-screen" :class="{'active': qbStep===1, 'prev': qbStep>1}">
            <template x-if="qbPkg">
                <div class="qb-pkg-box">
                    <div class="qb-pkg-nm" x-text="qbPkg.name"></div>
                    <div class="qb-pkg-pr" x-text="qbPkg.price>0?n(qbPkg.price)+' دج':'حسب الطلب'"></div>
                </div>
            </template>
            <div class="qb-field">
                <label class="qb-label">الاسم الكامل *</label>
                <input type="text" x-model="qbForm.name" class="qb-input" placeholder="اسمك الكامل">
            </div>
            <div class="qb-field">
                <label class="qb-label">رقم الهاتف *</label>
                <input type="tel" x-model="qbForm.phone" class="qb-input" placeholder="0550000000" dir="ltr">
            </div>
            <div class="qb-field">
                <label class="qb-label">البريد الإلكتروني *</label>
                <input type="email" x-model="qbForm.email" class="qb-input" placeholder="email@example.com" dir="ltr">
            </div>
            <!-- سعر الباقة دائماً ظاهر -->
            <template x-if="qbPkg && qbPkg.price>0">
                <div class="qb-price-bar">
                    <div class="qb-price-total-row">
                        <span class="qb-price-total-label">سعر الباقة</span>
                        <span class="qb-price-total-val" x-text="n(qbPkg.price)+' دج'"></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- ── الشاشة 2: تفاصيل الفعالية ── -->
        <div class="qb-screen" :class="{'active': qbStep===2, 'prev': qbStep>2}">
            <div class="qb-field">
                <label class="qb-label">تاريخ الحفل *</label>
                <input type="date" x-model="qbForm.event_date" @change="checkAvailability()" class="qb-input" dir="ltr">
                <div x-show="qbForm.event_date && qbAvailability" class="qb-avail" :class="qbAvailability==='available'?'available':'booked'">
                    <span x-text="qbAvailability==='available'?'✓ متاح':'✗ محجوز'"></span>
                </div>
            </div>
            <div class="qb-field">
                <label class="qb-label">وقت البداية *
                    <span x-show="qbTimeCost>0" style="color:#fb923c;margin-right:4px;" x-text="'(رسوم إضافية: +'+n(qbTimeCost)+' دج)'"></span>
                </label>
                <input type="time" x-model="qbForm.start_time" @change="recalcPrice()" class="qb-input" dir="ltr">
                <div class="qb-hint">الوقت الافتراضي 19:00 — الوقت المبكر يضيف رسوماً</div>
            </div>
            <div class="qb-field">
                <label class="qb-label">الولاية *
                    <span x-show="qbTravelCost>0" style="color:#fb923c;margin-right:4px;" x-text="'(تنقل: +'+n(qbTravelCost)+' دج)'"></span>
                </label>
                <select x-model="qbForm.wilaya_id" @change="fetchVenues(); recalcPrice();" class="qb-select">
                    <option value="">اختر الولاية...</option>
                    <template x-for="w in qbWilayas" :key="w.id">
                        <option :value="w.id" x-text="w.code+' — '+w.name"></option>
                    </template>
                </select>
            </div>
            <div class="qb-field">
                <label class="qb-label">قاعة الحفل *</label>
                <select x-model="qbForm.venue_id" @change="recalcPrice()" class="qb-select">
                    <option value="">اختر القاعة...</option>
                    <template x-for="v in qbVenues" :key="v.id">
                        <option :value="v.id" x-text="v.name"></option>
                    </template>
                    <option value="custom">أضف قاعة غير مدرجة...</option>
                </select>
            </div>
            <!-- حقل القاعة المخصصة -->
            <div class="qb-field" x-show="qbForm.venue_id==='custom'">
                <label class="qb-label">اسم القاعة *</label>
                <input type="text" x-model="qbForm.venue_custom" class="qb-input" placeholder="اكتب اسم وعنوان القاعة">
            </div>

            <!-- شريط السعر الحي -->
            <template x-if="qbPkg && qbPkg.price>0">
                <div class="qb-price-bar">
                    <div class="qb-price-row">
                        <span class="qb-price-row-label">سعر الباقة</span>
                        <span class="qb-price-row-val" x-text="n(qbPkg.price)+' دج'"></span>
                    </div>
                    <div class="qb-price-row" x-show="qbTravelCost>0">
                        <span class="qb-price-row-label">رسوم التنقل</span>
                        <span class="qb-price-row-val extra" x-text="'+'+n(qbTravelCost)+' دج'"></span>
                    </div>
                    <div class="qb-price-row" x-show="qbTimeCost>0">
                        <span class="qb-price-row-label">رسوم الوقت المبكر</span>
                        <span class="qb-price-row-val extra" x-text="'+'+n(qbTimeCost)+' دج'"></span>
                    </div>
                    <hr class="qb-price-divider">
                    <div class="qb-price-total-row">
                        <span class="qb-price-total-label">المجموع</span>
                        <span class="qb-price-total-val" x-text="n(qbPricing)+' دج'"></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- ── الشاشة 3: ملخص + برومو ── -->
        <div class="qb-screen" :class="{'active': qbStep===3, 'prev': qbStep>3}">
            <div class="qb-sum-row">
                <span class="qb-sum-label">الخدمة</span>
                <span class="qb-sum-val" x-text="det?.name||'—'"></span>
            </div>
            <div class="qb-sum-row">
                <span class="qb-sum-label">الباقة</span>
                <span class="qb-sum-val" x-text="qbPkg?.name||'—'"></span>
            </div>
            <div class="qb-sum-row">
                <span class="qb-sum-label">التاريخ</span>
                <span class="qb-sum-val" x-text="qbForm.event_date||'—'"></span>
            </div>
            <div class="qb-sum-row">
                <span class="qb-sum-label">الوقت</span>
                <span class="qb-sum-val" x-text="qbForm.start_time||'—'"></span>
            </div>

            <!-- شريط السعر مع البرومو -->
            <div class="qb-price-bar" style="margin-top:14px;">
                <div class="qb-price-row">
                    <span class="qb-price-row-label">سعر الباقة</span>
                    <span class="qb-price-row-val" x-text="n(qbPkg?.price||0)+' دج'"></span>
                </div>
                <div class="qb-price-row" x-show="qbTravelCost>0">
                    <span class="qb-price-row-label">رسوم التنقل</span>
                    <span class="qb-price-row-val extra" x-text="'+'+n(qbTravelCost)+' دج'"></span>
                </div>
                <div class="qb-price-row" x-show="qbTimeCost>0">
                    <span class="qb-price-row-label">رسوم الوقت المبكر</span>
                    <span class="qb-price-row-val extra" x-text="'+'+n(qbTimeCost)+' دج'"></span>
                </div>
                <div class="qb-price-row" x-show="qbPromoApplied">
                    <span class="qb-price-row-label" style="color:#4ade80;">خصم الكود</span>
                    <span class="qb-price-row-val" style="color:#4ade80;" x-text="'-'+n(qbPromoDiscount)+' دج'"></span>
                </div>
                <hr class="qb-price-divider">
                <div class="qb-price-total-row">
                    <span class="qb-price-total-label">الإجمالي</span>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span x-show="qbPromoApplied" class="qb-price-old" x-text="n(qbPricing)+' دج'"></span>
                        <span class="qb-price-total-val" x-text="n(qbPromoApplied?qbPricingFinal:qbPricing)+' دج'"></span>
                    </div>
                </div>
            </div>

            <!-- كود التخفيض -->
            <div class="qb-promo">
                <div class="qb-promo-lbl">كود التخفيض (اختياري)</div>
                <div class="qb-promo-row">
                    <input type="text" x-model="qbForm.promo_code" @keyup.enter="applyPromoQB()" class="qb-promo-input" placeholder="أدخل الكود" dir="ltr">
                    <button type="button" @click="applyPromoQB()" :disabled="qbPromoLoading" class="qb-promo-btn">تطبيق</button>
                </div>
                <div x-show="qbPromoMsg" class="qb-promo-msg" :class="qbPromoApplied?'ok':'err'" x-text="qbPromoMsg"></div>
            </div>

            <div x-show="qbErr" style="font-size:11px;color:#f87171;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:8px 11px;margin-top:10px;" x-text="qbErr"></div>
        </div>

        <!-- ── الشاشة 4: النجاح ── -->
        <div class="qb-screen qb-success-screen" :class="qbStep===4?'active':''">
            <div class="qb-success-icon">✓</div>
            <div style="font-size:16px;font-weight:900;color:#fff;margin-bottom:6px;">تم الحجز!</div>
            <div class="qb-success-ref" x-text="'#'+qbBookingRef"></div>
            <div style="font-size:12px;color:rgba(255,255,255,.45);line-height:1.7;margin-top:8px;">سنتواصل معك قريباً لتأكيد التفاصيل.</div>
            <div class="qb-success-btns">
                <a href="/" class="btn-home">← العودة للرئيسية</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="qb-foot" x-show="qbStep<4">
        <button x-show="qbStep>1" type="button" class="qb-btn-prev" @click="qbStep--">السابق</button>
        <button type="button" class="qb-btn-next"
                @click="qbNextStep()"
                :disabled="!qbCanProceed() || qbBusy"
                x-text="qbStep===3 ? (qbBusy?'جاري الإرسال...':'تأكيد الحجز ←') : 'التالي →'">
        </button>
    </div>
</div>