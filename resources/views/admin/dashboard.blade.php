@php
    $statCards = [
        ['label' => 'كل المقالات', 'value' => $stats['articles'], 'icon' => '✎', 'tone' => 'bg-blue-50 text-blue-700'],
        ['label' => 'المقالات المنشورة', 'value' => $stats['published'], 'icon' => '✓', 'tone' => 'bg-emerald-50 text-emerald-700'],
        ['label' => 'زيارات اليوم', 'value' => $stats['visits_today'], 'icon' => '↗', 'tone' => 'bg-amber-50 text-amber-700'],
        ['label' => 'زوار اليوم', 'value' => $stats['unique_today'], 'icon' => '●', 'tone' => 'bg-violet-50 text-violet-700'],
    ];
    $highestPageVisits = max(1, (int) $topPages->max('visits'));
@endphp

<x-layouts.admin title="لوحة التحكم">
    <section class="relative overflow-hidden rounded-[2rem] bg-[#172033] p-6 text-white shadow-xl shadow-[#172033]/10 sm:p-9">
        <div class="absolute -left-20 -top-24 size-72 rounded-full bg-accent/20 blur-3xl"></div>
        <div class="absolute -bottom-32 right-1/3 size-64 rounded-full bg-blue-400/10 blur-3xl"></div>
        <div class="relative flex flex-col justify-between gap-7 lg:flex-row lg:items-end">
            <div>
                <span class="inline-flex rounded-full border border-white/10 bg-white/8 px-3 py-1.5 text-xs font-bold text-white/70">مساحة المحتوى</span>
                <h2 class="mt-5 text-3xl font-bold sm:text-4xl">مرحبًا، {{ auth()->user()->name ?? 'كريم' }}</h2>
                <p class="mt-3 max-w-2xl leading-7 text-white/60">تابع أداء موقعك، ونظّم محتواك، وابدأ كتابة فكرتك القادمة من مكان واحد.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a class="inline-flex items-center justify-center rounded-full bg-accent px-6 py-3 font-bold text-white transition hover:-translate-y-0.5 hover:opacity-90" href="{{ route('admin.articles.create') }}">+ كتابة مقال</a>
                <a class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/8 px-6 py-3 font-bold text-white transition hover:bg-white/15" href="{{ route('home') }}" target="_blank">عرض الموقع ↗</a>
            </div>
        </div>
    </section>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($statCards as $stat)
            <div class="card flex items-center justify-between gap-5 !rounded-2xl !p-5">
                <div><p class="text-sm font-bold text-muted">{{ $stat['label'] }}</p><strong class="mt-2 block font-display text-3xl">{{ number_format($stat['value']) }}</strong></div>
                <span class="grid size-12 shrink-0 place-items-center rounded-2xl text-lg font-bold {{ $stat['tone'] }}">{{ $stat['icon'] }}</span>
            </div>
        @endforeach
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-[1.35fr_.65fr]">
        <div class="card !rounded-2xl !p-0">
            <div class="flex items-center justify-between gap-4 border-b border-line px-6 py-5">
                <div><h2 class="text-lg font-bold">أحدث المقالات</h2><p class="mt-1 text-sm text-muted">آخر ما تم إنشاؤه أو تحديثه</p></div>
                <a class="text-sm font-bold text-accent" href="{{ route('admin.articles.index') }}">كل المقالات ←</a>
            </div>
            <div class="divide-y divide-line">
                @forelse($recentArticles as $article)
                    <a class="group flex items-center gap-4 px-6 py-4 transition hover:bg-ink/[.025]" href="{{ route('admin.articles.edit',$article) }}">
                        @if($article->cover_image)<img class="size-14 shrink-0 rounded-2xl object-cover" src="{{ asset('storage/'.$article->cover_image) }}" alt="">@else<span class="grid size-14 shrink-0 place-items-center rounded-2xl bg-brand/5 font-display text-lg font-bold text-brand">{{ mb_substr($article->title('ar'), 0, 1) }}</span>@endif
                        <div class="min-w-0 grow"><p class="truncate font-bold transition group-hover:text-accent">{{ $article->title('ar') }}</p><span class="mt-1 block text-xs text-muted">{{ $article->category->name_ar }} · {{ $article->created_at->diffForHumans() }}</span></div>
                        <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold {{ $article->status==='published'?'bg-emerald-50 text-emerald-700':'bg-amber-50 text-amber-700' }}">{{ $article->status==='published'?'منشور':'مسودة' }}</span>
                    </a>
                @empty
                    <div class="p-10 text-center text-muted">لم تضف مقالات بعد.</div>
                @endforelse
            </div>
        </div>

        <aside class="grid content-start gap-6">
            <section class="card !rounded-2xl">
                <div><h2 class="text-lg font-bold">إجراءات سريعة</h2><p class="mt-1 text-sm text-muted">اختصارات للمهام المتكررة</p></div>
                <div class="mt-5 grid grid-cols-2 gap-3">
                    <a class="rounded-2xl border border-line p-4 font-bold transition hover:border-accent hover:bg-accent/5 hover:text-accent" href="{{ route('admin.articles.create') }}"><span class="mb-3 block text-xl">✎</span>مقال جديد</a>
                    <a class="rounded-2xl border border-line p-4 font-bold transition hover:border-accent hover:bg-accent/5 hover:text-accent" href="{{ route('admin.projects.create') }}"><span class="mb-3 block text-xl">◇</span>مشروع جديد</a>
                    <a class="rounded-2xl border border-line p-4 font-bold transition hover:border-accent hover:bg-accent/5 hover:text-accent" href="{{ route('admin.categories.create') }}"><span class="mb-3 block text-xl">▦</span>قسم جديد</a>
                    <a class="rounded-2xl border border-line p-4 font-bold transition hover:border-accent hover:bg-accent/5 hover:text-accent" href="{{ route('admin.tags.create') }}"><span class="mb-3 block text-xl">#</span>وسم جديد</a>
                </div>
            </section>
        </aside>
    </section>

    <section class="card mt-6 !rounded-2xl">
        <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-end">
            <div><h2 class="text-lg font-bold">الصفحات الأكثر زيارة</h2><p class="mt-1 text-sm text-muted">أداء الصفحات خلال آخر 30 يومًا</p></div>
            <span class="text-xs font-bold text-muted">إجمالي الزيارات لكل مسار</span>
        </div>
        <div class="mt-6 grid gap-x-10 gap-y-5 lg:grid-cols-2">
            @forelse($topPages as $page)
                <div>
                    <div class="flex items-center justify-between gap-4 text-sm"><span class="truncate text-muted" dir="ltr">{{ $page->path }}</span><strong class="font-display">{{ number_format($page->visits) }}</strong></div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-ink/5"><div class="h-full rounded-full bg-gradient-to-l from-accent to-brand" style="width:{{ max(4, round(($page->visits / $highestPageVisits) * 100)) }}%"></div></div>
                </div>
            @empty
                <p class="col-span-full rounded-2xl bg-ink/[.025] p-8 text-center text-muted">ستظهر إحصاءات الزيارات هنا بعد وصول أول الزوار.</p>
            @endforelse
        </div>
    </section>
</x-layouts.admin>
