@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">تعديل عنصر: {{ $item->name }}</h1>
        <div class="db-page-subtitle">خدمة: {{ $service->name }}</div>
    </div>
    <a href="{{ route('admin.services.items.index', $service) }}" class="db-btn-secondary">عودة للعناصر</a>
</div>

<div class="db-card">
    <div class="db-card-body">
        <form action="{{ route('admin.services.items.update', [$service, $item]) }}" method="POST">
            @csrf @method('PATCH')
            <div class="grid grid-cols-12 gap-4">

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>اسم العنصر <span class="text-red-600">*</span></label>
                    <input type="text" name="name" class="db-input @error('name') is-invalid @enderror"
                        value="{{ old('name', $item->name) }}" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>سعر الإضافة (د.ج)</label>
                    <input type="number" step="0.01" name="addon_price"
                        class="db-input @error('addon_price') is-invalid @enderror"
                        value="{{ old('addon_price', $item->addon_price) }}"
                        placeholder="اتركه فارغاً إذا لم يُباع كإضافة">
                    <small class="text-[var(--tx-secondary)] text-xs mt-1 block">
                        إذا تركته فارغاً، لن يظهر كإضافة للعميل.
                    </small>
                    @error('addon_price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label>الترتيب</label>
                    <input type="number" name="sort_order" class="db-input @error('sort_order') is-invalid @enderror"
                        value="{{ old('sort_order', $item->sort_order) }}">
                    @error('sort_order') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-12 mb-3 flex items-center gap-2 mt-2">
                    <input type="checkbox" name="is_active" id="isActive" value="1"
                        {{ old('is_active', $item->is_active) ? 'checked' : '' }} class="rounded border-gray-300">
                    <label for="isActive">عنصر مفعل</label>
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
