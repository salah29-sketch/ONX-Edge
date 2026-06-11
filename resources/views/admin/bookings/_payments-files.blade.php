{{-- ═══════════════════════════════════════════════════════
    PARTIAL: admin/bookings/_payments-files.blade.php
═══════════════════════════════════════════════════════ --}}

{{-- ───── PAYMENTS SECTION ─────────────────────────────── --}}
<div class="db-card mb-4" >
    <div class="db-card-header db-card-header-toggle flex justify-between items-center cursor-pointer"
         @click="openPayments = !openPayments" role="button" :aria-expanded="openPayments">
        <span><i class="bi bi-cash-coin me-2"></i> المدفوعات</span>
        <span class="flex items-center gap-2">
            @if($booking->total_price)
                <span class="badge" style="background:rgba(245,166,35,.18);color:#f5a623;font-size:13px;padding:6px 14px;border-radius:999px;">
                    {{ $booking->paymentPercent() }}% مسدّد
                </span>
            @endif
            <i class="fas fa-chevron-down db-collapse-icon"></i>
        </span>
    </div>

    <div x-show="openPayments" x-collapse class="db-card-body">

       

        {{-- ─── ملخص المدفوعات ─── --}}
        @if($booking->total_price)
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
            {{-- الإجمالي --}}
            <div style="background:#f8f9fb;border:1px solid #e5e7eb;border-radius:14px;padding:20px 16px;text-align:center;" class="payment-card-total">
                <div style="font-size:12px;color:#94a3b8;font-weight:700;margin-bottom:8px;letter-spacing:.04em;">الإجمالي</div>
                <div style="font-size:22px;font-weight:900;color:#1e293b;line-height:1;">{{ number_format($booking->total_price, 0) }}</div>
                <div style="font-size:13px;font-weight:700;color:#64748b;margin-top:4px;">DA</div>
            </div>
            {{-- مدفوع --}}
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:20px 16px;text-align:center;">
                <div style="font-size:12px;color:#16a34a;font-weight:700;margin-bottom:8px;letter-spacing:.04em;">مدفوع</div>
                <div style="font-size:22px;font-weight:900;color:#16a34a;line-height:1;">{{ number_format($booking->paidAmount(), 0) }}</div>
                <div style="font-size:13px;font-weight:700;color:#16a34a;margin-top:4px;">DA</div>
            </div>
            {{-- متبقي --}}
            @php $remaining = $booking->remainingAmount(); @endphp
            <div style="background:{{ $remaining > 0 ? '#fff1f2' : '#f0fdf4' }};border:1px solid {{ $remaining > 0 ? '#fecdd3' : '#bbf7d0' }};border-radius:14px;padding:20px 16px;text-align:center;">
                <div style="font-size:12px;font-weight:700;margin-bottom:8px;letter-spacing:.04em;color:{{ $remaining > 0 ? '#dc2626' : '#16a34a' }};">متبقي</div>
                <div style="font-size:22px;font-weight:900;line-height:1;color:{{ $remaining > 0 ? '#dc2626' : '#16a34a' }};">{{ number_format($remaining, 0) }}</div>
                <div style="font-size:13px;font-weight:700;margin-top:4px;color:{{ $remaining > 0 ? '#dc2626' : '#16a34a' }};">DA</div>
            </div>
        </div>

        {{-- شريط التقدم --}}
        <div style="margin-bottom:28px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:13px;font-weight:700;color:#64748b;">
                <span>تقدم السداد</span>
                <span>{{ $booking->paymentPercent() }}%</span>
            </div>
            <div style="height:10px;border-radius:999px;background:#e5e7eb;overflow:hidden;">
                <div style="width:{{ $booking->paymentPercent() }}%;height:100%;background:linear-gradient(90deg,#f5a623,#fbbf24);border-radius:999px;transition:width .4s ease;"></div>
            </div>
        </div>
        @endif

        {{-- ─── قائمة الدفعات ─── --}}
        @if($booking->payments->isNotEmpty())
        <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;margin-bottom:24px;">
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <thead>
                    <tr style="background:#f8f9fb;border-bottom:1px solid #e5e7eb;">
                        <th style="padding:12px 16px;text-align:right;font-weight:700;color:#64748b;font-size:12px;">النوع</th>
                        <th style="padding:12px 16px;text-align:right;font-weight:700;color:#64748b;font-size:12px;">الطريقة</th>
                        <th style="padding:12px 16px;text-align:right;font-weight:700;color:#64748b;font-size:12px;">المرجع</th>
                        <th style="padding:12px 16px;text-align:right;font-weight:700;color:#64748b;font-size:12px;">التاريخ</th>
                        <th style="padding:12px 16px;text-align:right;font-weight:700;color:#64748b;font-size:12px;">المبلغ</th>
                        <th style="padding:12px 16px;text-align:center;font-weight:700;color:#64748b;font-size:12px;">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->payments as $pay)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:12px 16px;">
                            <span style="background:rgba(245,166,35,.15);color:#b45309;font-size:12px;font-weight:700;padding:4px 10px;border-radius:999px;">
                                {{ $pay->typeLabel() }}
                            </span>
                        </td>
                        <td style="padding:12px 16px;color:#475569;">{{ $pay->methodLabel() }}</td>
                        <td style="padding:12px 16px;color:#94a3b8;">{{ $pay->reference ?: '—' }}</td>
                        <td style="padding:12px 16px;color:#475569;">{{ $pay->paid_at->format('d/m/Y') }}</td>
                        <td style="padding:12px 16px;">
                            <strong style="color:#16a34a;font-size:15px;">+ {{ number_format($pay->amount, 0) }} DA</strong>
                        </td>
                        <td style="padding:12px 16px;text-align:center;">
                            <form action="{{ route('admin.bookings.payments.destroy', $pay->id) }}" method="POST"
                                  onsubmit="return confirm('حذف هذه الدفعة؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="db-icon-btn db-delete-btn"
                                        style="background:#fff1f2;border:1px solid #fecdd3;border-radius:8px;padding:6px 10px;color:#dc2626;cursor:pointer;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p style="color:#94a3b8;font-size:14px;margin-bottom:20px;">لا توجد دفعات مسجلة بعد.</p>
        @endif

        {{-- ─── إضافة دفعة جديدة ─── --}}
        <div style="background:#f8f9fb;border:1px solid #e5e7eb;border-radius:14px;padding:20px;">
            <p class="db-label mb-3" style="font-weight:700;margin-bottom:14px;">
                <i class="bi bi-plus-circle me-1"></i> تسجيل دفعة جديدة
            </p>
            <form action="{{ route('admin.bookings.payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;gap:12px;align-items:flex-end;flex-wrap:wrap;">
                    <div>
                        <label class="db-label" style="font-size:12px;margin-bottom:4px;display:block;">المبلغ (DA)</label>
                        <input type="number" name="amount" class="db-input" min="1" step="1" required>
                    </div>
                    <div>
                        <label class="db-label" style="font-size:12px;margin-bottom:4px;display:block;">النوع</label>
                        <select name="type" class="db-input">
                            <option value="deposit">دفعة أولى</option>
                            <option value="partial" selected>جزئية</option>
                            <option value="final">نهائية</option>
                            <option value="full">كاملة</option>
                        </select>
                    </div>
                    <div>
                        <label class="db-label" style="font-size:12px;margin-bottom:4px;display:block;">الطريقة</label>
                        <select name="method" class="db-input">
                            <option value="cash">نقدًا</option>
                            <option value="bank_transfer">تحويل بنكي</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div>
                        <label class="db-label" style="font-size:12px;margin-bottom:4px;display:block;">رقم المرجع</label>
                        <input type="text" name="reference" class="db-input" placeholder="اختياري">
                    </div>
                    <div>
                        <label class="db-label" style="font-size:12px;margin-bottom:4px;display:block;">تاريخ الدفع</label>
                        <input type="date" name="paid_at" class="db-input"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div>
                        <button type="submit" class="db-btn-success" style="width:44px;height:44px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:10px;">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
                <div style="margin-top:10px;">
                    <input type="text" name="notes" class="db-input"
                           placeholder="ملاحظة (اختياري)" style="max-width:60%;">
                </div>
            </form>
        </div>

    </div>
</div>

{{-- ───── FILES SECTION ─────────────────────────────────── --}}
<div class="db-card mb-4">
    <div class="db-card-header db-card-header-toggle flex justify-between items-center cursor-pointer"
         @click="openFiles = !openFiles" role="button" :aria-expanded="openFiles">
        <span><i class="bi bi-folder2-open me-2"></i> ملفات المشروع</span>
        <span class="flex items-center gap-2">
            <span class="db-badge db-badge-secondary">{{ $booking->files->count() }} ملف</span>
            <i class="fas fa-chevron-down db-collapse-icon"></i>
        </span>
    </div>
    <div x-show="openFiles" x-collapse class="db-card-body">

        {{-- رابط الفيديو النهائي --}}
        <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:14px;padding:18px;margin-bottom:20px;">
            <p class="db-label mb-2" style="font-weight:700;margin-bottom:10px;">
                <i class="bi bi-link-45deg me-1"></i> رابط الفيديو النهائي (يظهر للعميل)
            </p>
            <form action="{{ route('admin.bookings.finalVideo', $booking) }}" method="POST"
                  style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
                @csrf @method('PATCH')
                <div style="flex:1;min-width:200px;">
                    <input type="text" name="final_video_path" value="{{ $booking->final_video_path }}"
                           placeholder="رابط فيديو أو مسار الملف" class="db-input">
                </div>
                <div>
                    <button type="submit" class="db-btn-primary" style="white-space:nowrap;">
                        <i class="bi bi-check2"></i> حفظ
                    </button>
                </div>
            </form>
        </div>

        {{-- قائمة الملفات --}}
        @if($booking->files->isNotEmpty())
        <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;margin-bottom:20px;">
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <thead>
                    <tr style="background:#f8f9fb;border-bottom:1px solid #e5e7eb;">
                        <th style="padding:12px 16px;text-align:right;font-weight:700;color:#64748b;font-size:12px;">الاسم</th>
                        <th style="padding:12px 16px;text-align:right;font-weight:700;color:#64748b;font-size:12px;">النوع</th>
                        <th style="padding:12px 16px;text-align:right;font-weight:700;color:#64748b;font-size:12px;">الحجم</th>
                        <th style="padding:12px 16px;text-align:center;font-weight:700;color:#64748b;font-size:12px;">مرئي للعميل</th>
                        <th style="padding:12px 16px;text-align:right;font-weight:700;color:#64748b;font-size:12px;">تاريخ الرفع</th>
                        <th style="padding:12px 16px;text-align:center;font-weight:700;color:#64748b;font-size:12px;">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->files as $file)
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:12px 16px;">
                            <i class="bi {{ $file->typeIcon() }}" style="color:{{ $file->typeColor() }};margin-left:6px;"></i>
                            {{ $file->label }}
                        </td>
                        <td style="padding:12px 16px;color:#64748b;">{{ strtoupper($file->type) }}</td>
                        <td style="padding:12px 16px;color:#94a3b8;">{{ $file->humanSize() ?: '—' }}</td>
                        <td style="padding:12px 16px;text-align:center;">
                            <form action="{{ route('admin.bookings.files.toggle', $file->id) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        style="border-radius:999px;font-size:12px;font-weight:700;padding:4px 12px;cursor:pointer;border:none;
                                        background:{{ $file->is_visible ? 'rgba(34,197,94,.15)' : 'rgba(148,163,184,.15)' }};
                                        color:{{ $file->is_visible ? '#16a34a' : '#94a3b8' }};">
                                    {{ $file->is_visible ? '✅ مرئي' : '🔒 مخفي' }}
                                </button>
                            </form>
                        </td>
                        <td style="padding:12px 16px;color:#475569;">{{ $file->created_at->format('d/m/Y') }}</td>
                        <td style="padding:12px 16px;text-align:center;">
                            <div style="display:flex;gap:6px;justify-content:center;">
                                <a href="{{ asset($file->path) }}" target="_blank"
                                   style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:6px 10px;color:#2563eb;text-decoration:none;">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                <form action="{{ route('admin.bookings.files.destroy', $file->id) }}" method="POST"
                                      onsubmit="return confirm('حذف هذا الملف؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="background:#fff1f2;border:1px solid #fecdd3;border-radius:8px;padding:6px 10px;color:#dc2626;cursor:pointer;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p style="color:#94a3b8;font-size:14px;margin-bottom:20px;">لا توجد ملفات مرفوعة بعد.</p>
        @endif

        {{-- رفع ملف جديد --}}
        <div style="background:#f8f9fb;border:1px solid #e5e7eb;border-radius:14px;padding:20px;">
            <p class="db-label mb-3" style="font-weight:700;margin-bottom:14px;">
                <i class="bi bi-upload me-1"></i> رفع ملف جديد
            </p>
            <form action="{{ route('admin.bookings.files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <div style="display:grid;grid-template-columns:2fr 1fr 2fr auto auto auto;gap:12px;align-items:flex-end;flex-wrap:wrap;">
                    <div>
                        <label class="db-label" style="font-size:12px;margin-bottom:4px;display:block;">اسم الملف (يظهر للعميل)</label>
                        <input type="text" name="label" class="db-input"
                               placeholder="مثال: الفيديو النهائي" required>
                    </div>
                    <div>
                        <label class="db-label" style="font-size:12px;margin-bottom:4px;display:block;">النوع</label>
                        <select name="type" class="db-input">
                            <option value="video">فيديو</option>
                            <option value="zip">ZIP صور</option>
                            <option value="pdf">PDF</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div>
                        <label class="db-label" style="font-size:12px;margin-bottom:4px;display:block;">الملف (حد: 500 MB)</label>
                        <input type="file" name="file" class="db-input" required>
                    </div>
                    <div style="text-align:center;">
                        <label class="db-label" style="font-size:12px;margin-bottom:4px;display:block;">مرئي؟</label>
                        <input type="checkbox" name="is_visible" value="1" checked
                               style="width:18px;height:18px;margin-top:10px;">
                    </div>
                    <div>
                        <button type="submit" class="db-btn-success"
                                style="width:44px;height:44px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:10px;">
                            <i class="bi bi-upload"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>