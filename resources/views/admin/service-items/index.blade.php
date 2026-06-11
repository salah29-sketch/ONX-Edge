@extends('layouts.admin')

@section('content')
<div class="db-page-head">
    <div>
        <h1 class="db-page-title">عناصر خدمة: {{ $service->name }}</h1>
        <div class="db-page-subtitle">إدارة العناصر المتاحة — المشمولة في الباقات أو كإضافات للعميل.</div>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.services.edit', $service) }}" class="db-btn-secondary">عودة للخدمة</a>
        <a href="{{ route('admin.services.items.create', $service) }}" class="db-btn-primary">+ إضافة عنصر</a>
    </div>
</div>

<div class="db-card">
    <div class="db-card-body">
        @if($items->isEmpty())
            <p class="text-center text-[var(--tx-secondary)] py-8">لا توجد عناصر بعد. أضف أول عنصر لهذه الخدمة.</p>
        @else
        <table class="db-table w-full">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>سعر الإضافة</th>
                    <th>الترتيب</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>
                        @if($item->addon_price !== null)
                            <span class="text-green-400">{{ number_format($item->addon_price) }} د.ج</span>
                        @else
                            <span class="text-[var(--tx-secondary)] text-sm">مشمول فقط</span>
                        @endif
                    </td>
                    <td>{{ $item->sort_order }}</td>
                    <td>
                        @if($item->is_active)
                            <span class="badge-success">نشط</span>
                        @else
                            <span class="badge-danger">معطل</span>
                        @endif
                    </td>
                    <td class="flex gap-2">
                        <a href="{{ route('admin.services.items.edit', [$service, $item]) }}" class="db-btn-icon-edit">✎</a>
                        <form action="{{ route('admin.services.items.destroy', [$service, $item]) }}" method="POST" onsubmit="return confirm('حذف هذا العنصر؟')">
                            @csrf @method('DELETE')
                            <button class="db-btn-icon-delete">✕</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
