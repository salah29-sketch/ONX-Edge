@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">تعديل الباقة: {{ $package->name }}</h1>
        <div class="db-page-subtitle">تعديل بيانات وتفاصيل الباقة الحالية.</div>
    </div>
    <a href="{{ route('admin.packages.index') }}" class="db-btn-secondary">عودة للقائمة</a>
</div>

<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.packages.update', $package) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>الخدمة المرتبطة <span class="text-red-600">*</span></label>
                    <select name="service_id" class="db-input @error('service_id') is-invalid @enderror" required>
                        <option value="">-- اختر الخدمة --</option>
                        @foreach($services as $id => $name)
                            <option value="{{ $id }}" {{ old('service_id', $package->service_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('service_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>اسم الباقة <span class="text-red-600">*</span></label>
                    <input type="text" name="name" class="db-input @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>العنوان الفرعي (Subtitle)</label>
                    <input type="text" name="subtitle" class="db-input @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $package->subtitle) }}">
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>مدة التنفيذ (Duration)</label>
                    <input type="text" name="duration" class="db-input @error('duration') is-invalid @enderror" value="{{ old('duration', $package->duration) }}">
                </div>

                <div class="col-span-12 md:col-span-4 mb-3">
                    <label>السعر (د.ج) <span class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="price" class="db-input @error('price') is-invalid @enderror" value="{{ old('price', $package->price) }}" required>
                </div>

                <div class="col-span-12 md:col-span-4 mb-3">
                    <label>السعر القديم (د.ج)</label>
                    <input type="number" step="0.01" name="old_price" class="db-input @error('old_price') is-invalid @enderror" value="{{ old('old_price', $package->old_price) }}">
                </div>

                <div class="col-span-12 md:col-span-4 mb-3">
                    <label>ملاحظة السعر</label>
                    <input type="text" name="price_note" class="db-input @error('price_note') is-invalid @enderror" value="{{ old('price_note', $package->price_note) }}">
                </div>

                <div class="col-span-12 mb-3">
                    <label>الوصف</label>
                    <textarea name="description" rows="3" class="db-input">{{ old('description', $package->description) }}</textarea>
                </div>

                {{-- ── عناصر الباقة ── --}}
                <div class="col-span-12 mb-3">
                    <label class="block mb-2">عناصر الباقة</label>

                    <div class="rounded-lg border border-[var(--card-border)] bg-[var(--body-bg)] p-3">
                    {{-- العناصر بدون سعر (مشمولة) --}}
                    <div id="items-free" class="space-y-2 mb-1"></div>

                    {{-- فاصل --}}
                    <div id="items-divider" class="hidden my-2 border-t border-dashed border-[var(--card-border)]"></div>

                    {{-- العناصر بسعر (إضافات) --}}
                    <div id="items-paid" class="space-y-2 mb-2"></div>

                    @if(isset($serviceItems) && $serviceItems->isNotEmpty())
                    <button type="button" onclick="openItemPicker()"
                        class="text-sm px-3 py-1.5 rounded-lg border border-dashed border-orange-500/40 text-orange-400 hover:bg-orange-500/10 transition">
                        + إضافة عنصر
                    </button>
                    @else
                    <p class="text-sm text-[var(--tx-secondary)]">لا توجد عناصر. <a href="{{ route('admin.services.items.index', $package->service_id) }}" class="text-orange-400 underline">أضف عناصر أولاً</a></p>
                    @endif

                    </div>
                    <input type="hidden" name="service_items_json" id="items-hidden">
                </div>

                {{-- Picker popup --}}
                <div id="item-picker" class="hidden col-span-12 mb-3 p-3 rounded-lg border border-[var(--card-border)] bg-[var(--body-bg)]">
                    <p class="text-sm font-bold mb-2">اختر عنصراً:</p>
                    <div id="picker-list" class="space-y-1 max-h-60 overflow-y-auto"></div>
                    <button type="button" onclick="closePicker()" class="mt-2 text-sm text-[var(--tx-secondary)] hover:text-red-400">إلغاء</button>
                </div>

                <div class="col-span-12 md:col-span-3 mb-3">
                    <label>الترتيب <span class="text-red-600">*</span></label>
                    <input type="number" name="sort_order" class="db-input @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $package->sort_order) }}" required>
                </div>

                <div class="col-span-12 md:col-span-9 mb-3 flex items-center gap-4 mt-4">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" class="rounded border-gray-300" id="isActive" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                        <label for="isActive">باقة مفعلة</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" class="rounded border-gray-300" id="isFeatured" value="1" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}>
                        <label for="isFeatured">باقة مميزة</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_buildable" class="rounded border-gray-300" id="isBuildable" value="1" {{ old('is_buildable', $package->is_buildable) ? 'checked' : '' }}>
                        <label for="isBuildable">باقة قابلة للبناء</label>
                    </div>
                </div>
            </div>

            <hr>
            <div class="flex justify-end">
                <button type="submit" class="db-btn-primary">حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// كل العناصر المتاحة للخدمة
const allItems = @json($serviceItems ?? []);

// العناصر المحفوظة في الباقة
const savedItems = @json($selectedItems ?? []);

// العناصر المختارة حالياً { id: {id, name, addon_price} }
let chosen = {};

function renderChosen() {
    const free = document.getElementById('items-free');
    const paid = document.getElementById('items-paid');
    const divider = document.getElementById('items-divider');

    free.innerHTML = '';
    paid.innerHTML = '';

    const freeItems = Object.values(chosen).filter(i => !i.addon_price);
    const paidItems = Object.values(chosen).filter(i => i.addon_price);

    freeItems.forEach(item => free.appendChild(makeRow(item)));
    paidItems.forEach(item => paid.appendChild(makeRow(item)));

    divider.classList.toggle('hidden', freeItems.length === 0 || paidItems.length === 0);
}

function makeRow(item) {
    const row = document.createElement('div');
    row.className = 'flex items-center gap-2 item-row';
    row.dataset.id = item.id;
    const badge = item.addon_price
        ? `<span class="text-xs text-orange-400 border border-orange-400/30 rounded px-1">${Number(item.addon_price).toLocaleString()} د.ج</span>`
        : '';
    row.innerHTML = `
        <span class="text-green-400 font-bold text-lg">✓</span>
        <span class="flex-1 text-sm">${item.name}</span>
        ${badge}
        <button type="button" onclick="removeItem(${item.id})" class="text-red-400 hover:text-red-300 px-2 text-lg">✕</button>
    `;
    return row;
}

function openItemPicker() {
    const picker = document.getElementById('item-picker');
    const list = document.getElementById('picker-list');
    picker.classList.remove('hidden');

    // فقط العناصر غير المختارة
    const available = allItems.filter(i => !chosen[i.id]);
    list.innerHTML = '';

    if (available.length === 0) {
        list.innerHTML = '<p class="text-sm text-[var(--tx-secondary)]">تم اختيار جميع العناصر.</p>';
        return;
    }

    available.forEach(item => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'w-full text-right px-3 py-2 rounded hover:bg-[var(--onx-orange-soft)] text-sm flex items-center justify-between gap-2';
        const price = item.addon_price ? `<span class="text-xs text-orange-400">${Number(item.addon_price).toLocaleString()} د.ج</span>` : '';
        btn.innerHTML = `<span>${item.name}</span>${price}`;
        btn.onclick = () => selectItem(item);
        list.appendChild(btn);
    });
}

function selectItem(item) {
    chosen[item.id] = item;
    renderChosen();
    closePicker();
    saveHidden();
}

function removeItem(id) {
    delete chosen[id];
    renderChosen();
    saveHidden();
}

function closePicker() {
    document.getElementById('item-picker').classList.add('hidden');
}

function saveHidden() {
    document.getElementById('items-hidden').value = JSON.stringify(Object.keys(chosen));
}

document.querySelector('form').addEventListener('submit', saveHidden);

// تحميل المحفوظ
savedItems.forEach(item => { chosen[item.id] = item; });
renderChosen();
saveHidden();
</script>
@endpush
