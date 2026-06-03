@extends('layouts.front_tailwind')

@section('title', 'ONX | الصفحة غير موجودة')
@section('meta_description', 'الصفحة التي تبحث عنها غير موجودة.')

@section('content')
<section class="relative isolate min-h-[80vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_40%,rgba(255,106,0,0.08),transparent_60%)]"></div>
    </div>

    <div class="mx-auto max-w-2xl px-6 py-20 text-center">
        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold text-white/50 backdrop-blur">
            <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
            خطأ 404
        </div>

        <h1 class="text-6xl font-black text-white sm:text-8xl">
            4<span class="text-orange-500">0</span>4
        </h1>

        <p class="mt-4 text-lg font-bold text-white/80">الصفحة غير موجودة</p>
        <p class="mt-2 text-sm leading-7 text-white/50">
            الصفحة التي تبحث عنها ربما حُذفت أو نُقلت أو لم تكن موجودة أصلاً.
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="/"
               class="inline-flex items-center justify-center rounded-full bg-orange-500 px-6 py-3 text-sm font-black text-black transition duration-300 hover:-translate-y-1 hover:bg-orange-400">
                العودة للرئيسية
            </a>
            <a href="/portfolio"
               class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/5 px-6 py-3 text-sm font-extrabold text-white transition duration-300 hover:-translate-y-1 hover:border-orange-500/50 hover:bg-orange-500/10">
                استكشف أعمالنا
            </a>
        </div>
    </div>
</section>
@endsection
