@php
    $navigation = [
        ['label' => 'نظرة عامة', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => '⌂'],
        ['label' => 'المقالات', 'route' => 'admin.articles.index', 'pattern' => 'admin.articles.*', 'icon' => '✎'],
        ['label' => 'الأقسام', 'route' => 'admin.categories.index', 'pattern' => 'admin.categories.*', 'icon' => '▦'],
        ['label' => 'الوسوم', 'route' => 'admin.tags.index', 'pattern' => 'admin.tags.*', 'icon' => '#'],
        ['label' => 'المشاريع', 'route' => 'admin.projects.index', 'pattern' => 'admin.projects.*', 'icon' => '◇'],
        ['label' => 'الخبرات', 'route' => 'admin.experiences.index', 'pattern' => 'admin.experiences.*', 'icon' => '◷'],
        ['label' => 'وسائل التواصل', 'route' => 'admin.social-links.index', 'pattern' => 'admin.social-links.*', 'icon' => '↗'],
        ['label' => 'رسائل التواصل', 'route' => 'admin.contact-messages.index', 'pattern' => 'admin.contact-messages.*', 'icon' => '✉', 'badge' => $unreadContactMessages],
        ['label' => 'الملف والتواصل', 'route' => 'admin.profile.edit', 'pattern' => 'admin.profile.*', 'icon' => '●'],
    ];
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $title ?? 'لوحة التحكم' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-[#f3f5f4]">
    <div data-admin-backdrop class="fixed inset-0 z-40 hidden bg-ink/50 backdrop-blur-sm lg:hidden"></div>
    <aside data-admin-sidebar class="fixed inset-y-0 right-0 z-50 flex w-72 translate-x-full flex-col overflow-y-auto bg-[#111827] px-5 py-6 text-white shadow-2xl transition-transform duration-300 lg:translate-x-0">
        <div class="flex items-center justify-between gap-4 px-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <span class="grid size-12 place-items-center overflow-hidden rounded-2xl bg-[#08101d] p-1"><img class="h-full w-full object-contain" src="{{ asset('ks.png') }}" alt="KS"></span>
                <span><strong class="block font-display text-base">لوحة الإدارة</strong><small class="mt-0.5 block text-white/45">Kareem Shalaby</small></span>
            </a>
            <button data-admin-menu-close type="button" class="grid size-9 place-items-center rounded-xl bg-white/10 text-xl lg:hidden" aria-label="إغلاق القائمة">×</button>
        </div>

        <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs text-white/45">مساحة العمل</p>
            <p class="mt-1 truncate text-sm font-bold">{{ auth()->user()->email }}</p>
        </div>

        <nav class="mt-7 grid gap-1.5 text-sm font-bold">
            @foreach($navigation as $item)
                <a class="group flex items-center gap-3 rounded-2xl px-3 py-3 transition {{ request()->routeIs($item['pattern']) ? 'bg-accent text-white shadow-lg shadow-black/15' : 'text-white/65 hover:bg-white/8 hover:text-white' }}" href="{{ route($item['route']) }}">
                    <span class="grid size-9 place-items-center rounded-xl {{ request()->routeIs($item['pattern']) ? 'bg-white/15' : 'bg-white/6 group-hover:bg-white/10' }}">{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                    @if(($item['badge'] ?? 0) > 0)<span class="me-auto rounded-full bg-red-400 px-2 py-0.5 text-xs text-white">{{ $item['badge'] }}</span>@endif
                </a>
            @endforeach
        </nav>

        <div class="mt-auto grid gap-2 border-t border-white/10 pt-5 text-sm font-bold">
            <a class="flex items-center justify-between rounded-2xl px-4 py-3 text-white/65 transition hover:bg-white/8 hover:text-white" href="{{ route('home') }}" target="_blank"><span>عرض الموقع</span><span>↗</span></a>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="w-full rounded-2xl px-4 py-3 text-start text-red-300 transition hover:bg-red-400/10 hover:text-red-200">تسجيل الخروج</button></form>
        </div>
    </aside>

    <div class="min-h-screen lg:mr-72">
        <header class="sticky top-0 z-30 border-b border-line/80 bg-[#f3f5f4]/90 backdrop-blur-xl">
            <div class="flex h-20 items-center justify-between gap-4 px-5 sm:px-8 lg:px-10">
                <div class="flex min-w-0 items-center gap-3">
                    <button data-admin-menu-open type="button" class="grid size-11 shrink-0 place-items-center rounded-2xl border border-line bg-surface text-xl shadow-sm lg:hidden" aria-label="فتح القائمة">☰</button>
                    <div class="min-w-0"><p class="text-xs font-bold text-accent">KS ADMIN</p><h1 class="truncate font-display text-lg font-bold sm:text-xl">{{ $title ?? 'لوحة التحكم' }}</h1></div>
                </div>
                <div class="flex items-center gap-3">
                    <a class="hidden rounded-full border border-line bg-surface px-4 py-2.5 text-sm font-bold transition hover:border-accent hover:text-accent sm:inline-flex" href="{{ route('admin.articles.create') }}">+ مقال جديد</a>
                    <span class="grid size-11 place-items-center rounded-2xl bg-brand font-display text-sm font-bold text-white">{{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'K', 0, 1)) }}</span>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-[1600px] p-5 sm:p-8 lg:p-10">
            @if(session('success'))<div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 font-bold text-emerald-800 shadow-sm"><span class="grid size-8 place-items-center rounded-full bg-emerald-100">✓</span>{{ session('success') }}</div>@endif
            @if($errors->any())<div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-800 shadow-sm"><p class="font-bold">يرجى مراجعة البيانات التالية:</p><ul class="mt-2 list-disc pe-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            {{ $slot }}
        </main>
    </div>
</body>
</html>
