<!-- ════════════════════════════════════════════════════════════════════
     BOOKING DRAWER COMPONENT — الحجز السريع بـ 3 خطوات
     استخدام: @include('booking-drawer')
     ════════════════════════════════════════════════════════════════════ -->

<style>
/* ══ QUICK BOOKING DRAWER (3-step multi-screen) ══ */
.qb-backdrop{position:fixed;inset:0;z-index:60;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);}
.qb-drawer{position:fixed;top:0;left:0;bottom:0;z-index:61;width:420px;max-width:100vw;background:#0d0d0d;border-right:1px solid rgba(var(--onx-brand-rgb),.2);box-shadow:4px 0 40px rgba(0,0,0,.6);display:flex;flex-direction:column;transform:translateX(-100%);transition:transform .38s cubic-bezier(.4,0,.2,1);}
.qb-drawer.open{transform:translateX(0);}

.qb-progress{display:flex;gap:6px;padding:14px 16px;border-bottom:1px solid rgba(255,255,255,.08);}
.qb-step{flex:1;height:4px;background:rgba(255,255,255,.1);border-radius:2px;transition:all .3s;}
.qb-step.active{background:var(--onx-brand);box-shadow:0 0 8px rgba(var(--onx-brand-rgb),.4);}
.qb-step.done{background:var(--onx-brand);}

.qb-screens{position:relative;flex:1;overflow:hidden;}
.qb-screen{position:absolute;inset:0;opacity:0;pointer-events:none;transition:all .35s cubic-bezier(.4,0,.2,1);transform:translateX(100%);overflow-y:auto;}
.qb-screen.active{opacity:1;pointer-events:auto;transform:translateX(0);}
.qb-screen.prev{transform:translateX(-100%);}
.qb-screen{padding:20px 16px;}

.qb-head{padding:14px 16px 0;border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0;display:flex;align-items:center;justify-content:space-between;}
.qb-close-btn{width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.08);border:none;color:rgba(255,255,255,.5);font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.2s;flex-shrink:0;}
.qb-close-btn:hover{background:rgba(255,255,255,.15);color:#fff;}
.qb-head-nav{display:flex;align-items:center;gap:8px;flex:1;}
.qb-back-btn{background:transparent;border:none;color:rgba(255,255,255,.5);cursor:pointer;padding:4px 8px;font-size:16px;transition:.2s;}
.qb-back-btn:hover{color:#fff;}
.qb-title{font-size:14px;font-weight:900;color:#fff;}

.qb-field{margin-bottom:14px;}
.qb-label{font-size:10px;font-weight:800;color:rgba(255,255,255,.6);margin-bottom:6px;display:block;text-transform:uppercase;letter-spacing:.06em;}
.qb-input{width:100%;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:11px 14px;font-size:13px;color:#fff;outline:none;font-family:inherit;transition:.2s;box-sizing:border-box;}
.qb-input:focus{border-color:rgba(var(--onx-brand-rgb),.5);box-shadow:0 0 0 3px rgba(var(--onx-brand-rgb),.1);}
.qb-input::placeholder{color:rgba(255,255,255,.2);}
.qb-select{width:100%;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:11px 14px;font-size:13px;color:#fff;outline:none;font-family:inherit;transition:.2s;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='rgba(255,255,255,.5)' d='M1 4l5 5 5-5'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:36px;box-sizing:border-box;}
.qb-select:focus{border-color:rgba(var(--onx-brand-rgb),.5);}
.qb-select option{background:#1a1a1a;color:#fff;}

.qb-pkg-box{background:rgba(var(--onx-brand-rgb),.08);border:1px solid rgba(var(--onx-brand-rgb),.25);border-radius:12px;padding:12px 14px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;}
.qb-pkg-left{flex:1;}
.qb-pkg-nm{font-size:13px;font-weight:800;color:#fff;margin-bottom:3px;}
.qb-pkg-change{font-size:10px;color:rgba(var(--onx-brand-rgb),.6);cursor:pointer;text-decoration:underline;}
.qb-pkg-pr{font-size:15px;font-weight:900;color:var(--onx-brand);}

.qb-avail{display:inline-flex;align-items:center;gap:5px;font-size:10px;font-weight:700;padding:4px 10px;border-radius:6px;margin-top:6px;}
.qb-avail.available{background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.3);color:#4ade80;}
.qb-avail.booked{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);color:#f87171;}

.qb-summary-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.06);font-size:12px;}
.qb-summary-row:last-child{border:none;}
.qb-summary-label{color:rgba(255,255,255,.5);}
.qb-summary-value{color:#fff;font-weight:700;}
.qb-summary-total{padding:12px 0;border-top:1px solid rgba(255,255,255,.1);margin-top:8px;font-size:18px;font-weight:900;color:var(--onx-brand);}
.qb-summary-old{color:rgba(255,255,255,.3);text-decoration:line-through;font-size:12px;}

.qb-promo{margin:14px 0;padding:12px;background:rgba(var(--onx-brand-rgb),.06);border:1px solid rgba(var(--onx-brand-rgb),.15);border-radius:10px;}
.qb-promo-label{font-size:10px;font-weight:700;color:rgba(255,255,255,.5);margin-bottom:6px;text-transform:uppercase;}
.qb-promo-field{display:flex;gap:6px;}
.qb-promo-input{flex:1;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:8px 12px;font-size:12px;color:#fff;font-family:inherit;text-transform:uppercase;text-align:center;box-sizing:border-box;}
.qb-promo-input:focus{border-color:rgba(var(--onx-brand-rgb),.4);}
.qb-promo-btn{background:rgba(var(--onx-brand-rgb),.15);border:1px solid rgba(var(--onx-brand-rgb),.3);border-radius:8px;padding:8px 14px;font-size:11px;font-weight:700;color:var(--onx-brand);cursor:pointer;transition:.2s;white-space:nowrap;}
.qb-promo-btn:hover{background:rgba(var(--onx-brand-rgb),.25);}
.qb-promo-msg{margin-top:6px;font-size:10px;padding:5px 8px;border-radius:6px;}
.qb-promo-msg.ok{background:rgba(34,197,94,.12);color:#4ade80;border:1px solid rgba(34,197,94,.2);}
.qb-promo-msg.err{background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.2);}

.qb-foot{padding:14px 16px;border-top:1px solid rgba(255,255,255,.07);flex-shrink:0;display:flex;gap:8px;}
.qb-btn-prev{flex:1;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.7);border-radius:10px;padding:11px;font-size:12px;font-weight:700;cursor:pointer;font-family:inherit;transition:.2s;}
.qb-btn-prev:hover{background:rgba(255,255,255,.1);}
.qb-btn-next{flex:1;background:var(--onx-brand);border:none;color:#000;border-radius:10px;padding:11px;font-size:12px;font-weight:900;cursor:pointer;font-family:inherit;transition:.2s;box-shadow:0 4px 16px rgba(var(--onx-brand-rgb),.25);}
.qb-btn-next:hover{filter:brightness(1.1);}
.qb-btn-next:disabled{opacity:.5;cursor:not-allowed;}

.qb-success-screen{text-align:center;padding:40px 20px;}
.qb-success-icon{width:64px;height:64px;margin:0 auto 16px;border-radius:50%;background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.3);display:flex;align-items:center;justify-content:center;font-size:28px;color:#4ade80;}
.qb-success-title{font-size:16px;font-weight:900;color:#fff;margin-bottom:6px;}
.qb-success-ref{display:inline-block;background:rgba(var(--onx-brand-rgb),.1);border:1px solid rgba(var(--onx-brand-rgb),.25);color:var(--onx-brand);font-weight:900;font-size:14px;padding:6px 16px;border-radius:99px;margin:8px 0;}
.qb-login-box{background:rgba(var(--onx-brand-rgb),.08);border:1px solid rgba(var(--onx-brand-rgb),.2);border-radius:12px;padding:12px;margin:16px 0;text-align:left;}
.qb-login-row{font-size:11px;margin-bottom:8px;color:#fff;}
.qb-login-row strong{display:block;color:var(--onx-brand);font-family:monospace;font-size:12px;margin-top:3px;}
.qb-login-row:last-child{margin-bottom:0;}
.qb-success-btns{display:flex;flex-direction:column;gap:8px;margin-top:16px;}
.qb-success-btns a{display:block;text-align:center;padding:10px;font-size:12px;font-weight:700;border-radius:10px;text-decoration:none;transition:.2s;}
.qb-success-btns .btn-pdf{background:var(--onx-brand);color:#000;}
.qb-success-btns .btn-pdf:hover{filter:brightness(1.1);}
.qb-success-btns .btn-home{background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.1);}
.qb-success-btns .btn-home:hover{background:rgba(255,255,255,.12);}

@media(max-width:767px){.qb-drawer,.qb-backdrop{display:none!important;}}
</style>

<!-- Backdrop -->
<div x-show="drawerOpen" x-cloak class="qb-backdrop" @click="closeDrawer()"></div>

<!-- الـ Drawer -->
<div class="qb-drawer" :class="drawerOpen?'open':''">
    
    <!-- Progress Bar -->
    <div class="qb-progress">
        <div class="qb-step" :class="qbStep>=1?'done':''" :class.active="qbStep===1"></div>
        <div class="qb-step" :class="qbStep>=2?'done':''" :class.active="qbStep===2"></div>
        <div class="qb-step" :class="qbStep===3?'active':qbStep>3?'done':''"></div>
    </div>

    <!-- Header -->
    <div class="qb-head">
        <div class="qb-head-nav">
            <button x-show="qbStep>1" type="button" class="qb-back-btn" @click="qbStep--">←</button>
            <div class="qb-title" x-text="qbStep===1?'معلوماتك':qbStep===2?'الفعالية':qbStep===3?'ملخص الحجز':'النجاح'"></div>
        </div>
        <button type="button" class="qb-close-btn" @click="closeDrawer()">✕</button>
    </div>

    <!-- Screens Container -->
    <div class="qb-screens">
        
        <!-- Screen 1: Personal Info -->
        <div class="qb-screen" :class="qbStep===1?'active':''" :class.prev="qbStep>1">
            <div x-show="qbPkg" class="qb-pkg-box">
                <div class="qb-pkg-left">
                    <div class="qb-pkg-nm" x-text="qbPkg.name"></div>
                </div>
                <div class="qb-pkg-pr" x-text="qbPkg.price>0?n(qbPkg.price)+' دج':'حسب الطلب'"></div>
            </div>
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
        </div>

        <!-- Screen 2: Event Details -->
        <div class="qb-screen" :class="qbStep===2?'active':''" :class.prev="qbStep>2">
            <div class="qb-field">
                <label class="qb-label">تاريخ الحفل *</label>
                <input type="date" x-model="qbForm.event_date" @change="checkAvailability()" class="qb-input" dir="ltr">
                <div x-show="qbForm.event_date && qbAvailability" class="qb-avail" :class="qbAvailability==='available'?'available':'booked'">
                    <span x-text="qbAvailability==='available'?'✓ متاح':'✗ محجوز'"></span>
                </div>
            </div>
            <div class="qb-field">
                <label class="qb-label">وقت البداية *</label>
                <input type="time" x-model="qbForm.start_time" class="qb-input" dir="ltr" value="19:00">
            </div>
            <div class="qb-field">
                <label class="qb-label">الولاية *</label>
                <select x-model="qbForm.wilaya_id" @change="fetchVenues()" class="qb-select">
                    <option value="">اختر الولاية...</option>
                    <template x-for="w in qbWilayas" :key="w.id">
                        <option :value="w.id" x-text="w.code+' — '+w.name"></option>
                    </template>
                </select>
            </div>
            <div class="qb-field">
                <label class="qb-label">قاعة الحفل *</label>
                <select x-model="qbForm.venue_id" class="qb-select">
                    <option value="">اختر القاعة...</option>
                    <template x-for="v in qbVenues" :key="v.id">
                        <option :value="v.id" x-text="v.name"></option>
                    </template>
                </select>
            </div>
        </div>

        <!-- Screen 3: Summary -->
        <div class="qb-screen" :class="qbStep===3?'active':''" :class.prev="qbStep>3">
            <div style="margin-bottom:16px;">
                <div class="qb-summary-row">
                    <span class="qb-summary-label">الخدمة</span>
                    <span class="qb-summary-value" x-text="det?.name||'—'"></span>
                </div>
                <div class="qb-summary-row">
                    <span class="qb-summary-label">الباقة</span>
                    <span class="qb-summary-value" x-text="qbPkg?.name||'—'"></span>
                </div>
                <div class="qb-summary-row">
                    <span class="qb-summary-label">التاريخ</span>
                    <span class="qb-summary-value" x-text="qbForm.event_date||'—'"></span>
                </div>
            </div>
            <div style="margin-bottom:12px;padding:12px 0;border-top:1px solid rgba(255,255,255,.1);">
                <div class="qb-summary-total">
                    <span x-text="n(qbPromoApplied?qbPricingFinal:qbPricing)+' دج'"></span>
                </div>
            </div>
            <div class="qb-promo">
                <div class="qb-promo-label">كود التخفيض (اختياري)</div>
                <div class="qb-promo-field">
                    <input type="text" x-model="qbForm.promo_code" @keyup.enter="applyPromoQB()" class="qb-promo-input" placeholder="أدخل الكود" dir="ltr">
                    <button type="button" @click="applyPromoQB()" :disabled="qbPromoLoading" class="qb-promo-btn" x-text="'تطبيق'"></button>
                </div>
                <div x-show="qbPromoMsg" class="qb-promo-msg" :class="qbPromoApplied?'ok':'err'" x-text="qbPromoMsg"></div>
            </div>
        </div>

        <!-- Screen 4: Success -->
        <div class="qb-screen qb-success-screen" :class="qbStep===4?'active':''">
            <div class="qb-success-icon">✓</div>
            <div class="qb-success-title">تم الحجز!</div>
            <div class="qb-success-ref" x-text="'#'+qbBookingRef"></div>
            <div class="qb-success-btns">
                <a href="/" class="btn-home">← العودة للرئيسية</a>
            </div>
        </div>
    </div>

    <!-- Footer Buttons -->
    <div class="qb-foot" x-show="qbStep<4">
        <button x-show="qbStep>1" type="button" class="qb-btn-prev" @click="qbStep--">السابق</button>
        <button type="button" class="qb-btn-next" @click="qbNextStep()" :disabled="!qbCanProceed()" x-text="qbStep===3?'تأكيد':'التالي'"></button>
    </div>
</div>