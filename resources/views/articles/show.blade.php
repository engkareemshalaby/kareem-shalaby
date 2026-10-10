<x-layouts.app :title="$article->seo_title ?: $article->title().' | كريم شلبي'" :description="$article->seo_description ?: $article->excerpt()" :canonical="route('articles.show',$article)" og-type="article">
@push('head')<script type="application/ld+json">{!! json_encode(['@@context'=>'https://schema.org','@@type'=>'Article','headline'=>$article->title(),'description'=>$article->seo_description ?: $article->excerpt(),'datePublished'=>$article->published_at?->toIso8601String(),'dateModified'=>$article->updated_at->toIso8601String(),'author'=>['@@type'=>'Person','name'=>'Kareem Shalaby'],'mainEntityOfPage'=>route('articles.show',$article)], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>@endpush
@php($isEnglish = app()->isLocale('en'))
<article class="container-site py-8 sm:py-12 lg:py-16">
    <nav class="mx-auto mb-10 flex max-w-5xl flex-wrap items-center gap-2 text-sm text-muted" aria-label="{{ $isEnglish ? 'Breadcrumb' : 'مسار التنقل' }}">
        <a class="transition hover:text-accent" href="{{ route('articles.index') }}">{{ $isEnglish ? 'Articles' : 'المقالات' }}</a>
        <span aria-hidden="true">/</span>
        <a class="font-bold text-ink transition hover:text-accent" href="{{ route('articles.index', ['category' => $article->category->slug]) }}">{{ $article->category->name() }}</a>
    </nav>

    <header class="mx-auto flex max-w-4xl flex-col items-center gap-6 text-center">
        <a href="{{ route('articles.index', ['category' => $article->category->slug]) }}" class="eyebrow !tracking-normal transition hover:border-accent">{{ $article->category->name() }}</a>
        <h1 class="text-3xl leading-[1.5] font-bold break-words text-balance sm:text-4xl lg:text-5xl">{{ $article->title() }}</h1>
        @if($article->excerpt())
            <p class="max-w-2xl text-lg leading-8 text-muted sm:text-xl sm:leading-9">{{ $article->excerpt() }}</p>
        @endif
        <div class="flex flex-wrap items-center justify-center gap-3 text-sm sm:gap-4">
            <span class="grid size-10 shrink-0 place-items-center rounded-full border border-accent/25 bg-accent/10 font-display text-xs font-bold text-accent" aria-hidden="true">KS</span>
            <div class="flex flex-col gap-1 text-start">
                <span class="font-bold text-ink">{{ $isEnglish ? 'Kareem Shalaby' : 'كريم شلبي' }}</span>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-muted">
                    <time datetime="{{ $article->published_at->toIso8601String() }}">{{ $article->published_at->translatedFormat('j F Y') }}</time>
                    <span aria-hidden="true">·</span>
                    <span>{{ max(1, ceil(str_word_count(strip_tags($article->body())) / 220)) }} {{ $isEnglish ? 'min read' : 'دقائق قراءة' }}</span>
                </div>
            </div>
        </div>
        @if($article->tags->isNotEmpty())
            <div class="flex flex-wrap justify-center gap-2">
                @foreach($article->tags as $tag)
                    <a class="rounded-full border border-line bg-surface px-3 py-1.5 text-sm font-bold text-muted transition hover:border-accent hover:text-accent" href="{{ route('articles.index', ['tag' => $tag->slug]) }}"><bdi>#{{ $tag->name }}</bdi></a>
                @endforeach
            </div>
        @endif
    </header>

    @if($article->cover_image)
        <figure class="mx-auto mt-10 max-w-5xl overflow-hidden rounded-2xl border border-line bg-surface shadow-lg shadow-brand/5 sm:mt-12 sm:rounded-3xl">
            <img class="aspect-video w-full object-cover" src="{{ asset('storage/'.$article->cover_image) }}" alt="{{ $article->title() }}">
        </figure>
    @endif

    <div class="mx-auto mt-10 max-w-4xl rounded-2xl border border-line bg-surface px-5 py-7 shadow-[0_12px_48px_rgba(23,32,51,0.03)] sm:mt-12 sm:rounded-3xl sm:px-10 sm:py-10 lg:px-12">
        <div class="prose-kareem article-content">
            {!! Str::markdown($article->body(), ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
        </div>
        <div class="mt-10 border-t border-line pt-6">
            <a class="inline-flex items-center gap-2 text-sm font-bold text-accent underline-offset-4 hover:underline" href="{{ route('articles.index') }}">
                <span aria-hidden="true">{{ $isEnglish ? '←' : '→' }}</span>
                {{ $isEnglish ? 'Back to all articles' : 'العودة إلى كل المقالات' }}
            </a>
        </div>
    </div>
</article>

@if($related->isNotEmpty())
    <section class="container-site pt-4 sm:pt-8" aria-labelledby="related-articles-heading">
        <div class="mx-auto max-w-5xl border-t border-line pt-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h2 id="related-articles-heading" class="text-2xl font-bold">{{ $isEnglish ? 'You may also like' : 'قد يهمك أيضًا' }}</h2>
                <a class="text-sm font-bold text-accent underline-offset-4 hover:underline" href="{{ route('articles.index') }}">{{ $isEnglish ? 'All articles' : 'كل المقالات' }}</a>
            </div>
            <div class="mt-6 grid gap-5 md:grid-cols-3">
                @foreach($related as $item)
                    <a class="card group flex flex-col gap-4 overflow-hidden !p-0" href="{{ route('articles.show', $item) }}">
                        @if($item->cover_image)
                            <img class="aspect-video w-full object-cover" src="{{ asset('storage/'.$item->cover_image) }}" alt="" loading="lazy">
                        @endif
                        <div class="flex grow flex-col gap-3 p-6">
                            <span class="text-xs font-bold text-accent">{{ $item->category->name() }}</span>
                            <h3 class="text-lg leading-relaxed font-bold transition group-hover:text-accent">{{ $item->title() }}</h3>
                            @if($item->excerpt())
                                <p class="line-clamp-2 text-sm leading-7 text-muted">{{ $item->excerpt() }}</p>
                            @endif
                            <span class="mt-auto pt-2 text-sm font-bold text-accent">{{ $isEnglish ? 'Read article →' : 'أكمل القراءة ←' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
</x-layouts.app>
