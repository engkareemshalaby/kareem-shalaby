@php($isEnglish = app()->isLocale('en'))
<x-layouts.app>
@push('head')<script type="application/ld+json">{!! json_encode(['@@context' => 'https://schema.org', '@@type' => 'Person', 'name' => 'Kareem Shalaby', 'alternateName' => 'كريم شلبي', 'jobTitle' => 'Software Engineer', 'url' => url('/')], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>@endpush
<div class="home-page">
    <section class="home-hero relative isolate overflow-hidden" data-home-hero>
        <div class="home-orbit home-orbit-one" aria-hidden="true"></div>
        <div class="home-orbit home-orbit-two" aria-hidden="true"></div>
        <div class="container-site relative grid min-h-[680px] items-center gap-14 py-20 lg:grid-cols-[1.1fr_.9fr] lg:py-28">
            <div class="home-intro relative z-10">
                <span class="home-kicker">{{ $isEnglish ? 'KAREEM SHALABY / PERSONAL SPACE' : 'كريم شلبي / مساحة شخصية' }}</span>
                <h1 class="home-title mt-8 max-w-3xl font-bold leading-[1.25] tracking-tight">{{ $isEnglish ? 'I try to leave' : 'أحاول أن أترك' }}<br><span>{{ $isEnglish ? 'a mark before I go.' : 'أثرا قبل رحيلي' }}</span></h1>
                <p class="mt-8 max-w-xl text-lg leading-9 text-muted sm:text-xl">{{ $isEnglish ? 'I am Kareem Shalaby. I build useful things and share what I learn about software, business, and life.' : 'أنا كريم شلبي. أبني ما ينفع، وأشارك ما أتعلمه في البرمجة والأعمال والحياة.' }}</p>
                <div class="mt-10 flex flex-wrap gap-3">
                    <a class="btn-primary home-primary" href="{{ route('articles.index') }}">{{ $isEnglish ? 'Explore the articles ↗' : 'استكشف المقالات ↗' }}</a>
                    <a class="btn-secondary home-secondary" href="{{ route('professional') }}">{{ $isEnglish ? 'Professional profile' : 'الملف المهني' }}</a>
                </div>
                <a class="home-scroll-link mt-14 inline-flex items-center gap-3 text-sm font-bold" href="#knowledge"><span class="home-scroll-line" aria-hidden="true"></span>{{ $isEnglish ? 'Scroll to explore' : 'مرر لتكتشف المزيد' }}</a>
            </div>
            <div class="home-art relative mx-auto w-full max-w-[500px]" data-home-art aria-hidden="true">
                <div class="home-art-halo"></div>
                <div class="home-art-panel">
                    <span class="home-art-index">01 / {{ $isEnglish ? 'A LIFE IN PROGRESS' : 'رحلة مستمرة' }}</span>
                    <span class="home-art-monogram">KS<span>.</span></span>
                    <span class="home-art-rule"></span>
                    <span class="home-art-caption">{{ $isEnglish ? 'Ideas · Work · Impact' : 'أفكار · عمل · أثر' }}</span>
                </div>
                <span class="home-art-stamp">{{ $isEnglish ? 'MADE WITH PURPOSE' : 'بنيةٍ وأثر' }}</span>
            </div>
        </div>
    </section>

    <section id="knowledge" class="container-site py-20 lg:py-28" data-home-reveal>
        <div class="home-section-heading mb-10 flex flex-wrap items-end justify-between gap-5">
            <div><span class="home-label">01 / {{ $isEnglish ? 'KNOWLEDGE' : 'المعرفة' }}</span><h2 class="mt-4 text-3xl font-bold sm:text-4xl">{{ $isEnglish ? 'Paths worth exploring' : 'مسارات تستحق الاستكشاف' }}</h2></div>
            <p class="max-w-md leading-8 text-muted">{{ $isEnglish ? 'Thoughts and lessons from the things I build, read, and experience.' : 'أفكار ودروس من الأشياء التي أبنيها وأقرأ عنها وأعيشها.' }}</p>
        </div>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($categories as $category)
                <a href="{{ route('articles.index', ['category' => $category->slug]) }}" class="home-category group" data-home-reveal>
                    <div class="flex items-start justify-between gap-4"><span class="home-category-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="text-sm text-muted">{{ $category->articles_count }} {{ $isEnglish ? 'articles' : 'مقال' }}</span></div>
                    <h3 class="mt-12 text-2xl font-bold">{{ $category->name() }}</h3>
                    <p class="mt-3 line-clamp-2 leading-7 text-muted">{{ $category->description() }}</p>
                    <span class="mt-8 inline-flex items-center gap-2 font-bold text-accent">{{ $isEnglish ? 'Explore' : 'اكتشف' }} <span aria-hidden="true">↗</span></span>
                </a>
            @endforeach
        </div>
    </section>

    @if($featured)
        <section class="container-site py-8 lg:py-16" data-home-reveal>
            <div class="home-feature grid overflow-hidden rounded-[2rem] lg:grid-cols-2">
                <div class="flex flex-col justify-center p-8 sm:p-12 lg:p-16">
                    <span class="home-label !text-[#ff8654]">02 / {{ $isEnglish ? 'EDITOR’S PICK' : 'اختيار مميز' }}</span>
                    <p class="mt-8 text-sm text-white/60">{{ $featured->category->name() }} · {{ $featured->published_at->translatedFormat('j F Y') }}</p>
                    <h2 class="mt-5 text-3xl leading-snug font-bold sm:text-4xl">{{ $featured->title() }}</h2>
                    <p class="mt-5 line-clamp-3 text-lg leading-8 text-white/70">{{ $featured->excerpt() }}</p>
                    <a href="{{ route('articles.show', $featured) }}" class="mt-9 inline-flex items-center gap-2 font-bold text-[#ff8654]">{{ $isEnglish ? 'Read the article' : 'اقرأ المقال' }} <span aria-hidden="true">↗</span></a>
                </div>
                @if($featured->cover_image)
                    <a href="{{ route('articles.show', $featured) }}" class="block overflow-hidden"><img class="h-full min-h-72 w-full object-cover transition duration-700 hover:scale-105" src="{{ asset('storage/'.$featured->cover_image) }}" alt="{{ $featured->title() }}" loading="lazy"></a>
                @else
                    <div class="home-feature-art grid min-h-72 place-items-center font-display text-8xl font-bold" aria-hidden="true">KS<span>.</span></div>
                @endif
            </div>
        </section>
    @endif

    <section class="container-site py-20 lg:py-28" data-home-reveal>
        <div class="mb-10 flex flex-wrap items-end justify-between gap-5"><div><span class="home-label">03 / {{ $isEnglish ? 'JOURNAL' : 'المدونة' }}</span><h2 class="mt-4 text-3xl font-bold sm:text-4xl">{{ $isEnglish ? 'Latest writing' : 'آخر ما كتبت' }}</h2></div><a class="home-text-link font-bold" href="{{ route('articles.index') }}">{{ $isEnglish ? 'All articles ↗' : 'كل المقالات ↗' }}</a></div>
        <div class="grid gap-6 md:grid-cols-3">
            @foreach($articles->take(3) as $article)
                <article class="home-article" data-home-reveal>
                    @if($article->cover_image)<a class="block overflow-hidden rounded-2xl" href="{{ route('articles.show', $article) }}"><img class="aspect-[4/3] w-full object-cover transition duration-700 hover:scale-105" src="{{ asset('storage/'.$article->cover_image) }}" alt="{{ $article->title() }}" loading="lazy"></a>@else<a class="home-article-placeholder grid aspect-[4/3] place-items-center rounded-2xl" href="{{ route('articles.show', $article) }}" aria-label="{{ $article->title() }}">KS.</a>@endif
                    <div class="mt-6 flex items-center justify-between gap-3 text-sm"><span class="font-bold text-accent">{{ $article->category->name() }}</span><time class="text-muted" datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->translatedFormat('j M Y') }}</time></div>
                    <h3 class="mt-4 text-xl leading-relaxed font-bold"><a href="{{ route('articles.show', $article) }}">{{ $article->title() }}</a></h3>
                    <p class="mt-3 line-clamp-2 leading-7 text-muted">{{ $article->excerpt() }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="home-about py-20 lg:py-28" data-home-reveal><div class="container-site grid gap-10 lg:grid-cols-[.8fr_1.2fr]"><div><span class="home-label">04 / {{ $isEnglish ? 'ABOUT' : 'عني' }}</span><h2 class="mt-5 text-3xl leading-snug font-bold sm:text-4xl">{{ $isEnglish ? 'Curiosity, work, and a little more meaning.' : 'فضول وعمل، وقليل من المعنى.' }}</h2></div><div class="space-y-5 text-lg leading-9 text-muted">@if($isEnglish)<p>I document tested lessons and questions I am still exploring. In family topics, I write about what I learn and apply. In Islamic topics, I cite sources and clarify the limits of my knowledge.</p><p>My professional profile brings together technical writing, business case studies, projects, and experience.</p>@else<p>أوثّق هنا الدروس التي اختبرتها، والأسئلة التي ما زلت أبحث فيها. في موضوعات الأسرة أكتب عما أتعلمه وأطبقه، وفي الموضوعات الشرعية أعتني بالمصادر وحدود ما أنسبه لنفسي.</p><p>أما الملف المهني فيجمع المحتوى التقني ودراسات الأعمال والمشاريع والخبرة في صفحة مستقلة.</p>@endif<a class="home-text-link inline-flex pt-3 font-bold" href="{{ route('contact.create') }}">{{ $isEnglish ? 'Get in touch ↗' : 'تواصل معي ↗' }}</a></div></div></section>
</div>
</x-layouts.app>
