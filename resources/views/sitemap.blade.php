{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach(['ar', 'en'] as $locale)
<url><loc>{{ route('home', ['locale' => $locale]) }}</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>
<url><loc>{{ route('articles.index', ['locale' => $locale]) }}</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
<url><loc>{{ route('professional', ['locale' => $locale]) }}</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
<url><loc>{{ route('projects.index', ['locale' => $locale]) }}</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
@foreach($articles as $article)<url><loc>{{ route('articles.show', ['locale' => $locale, 'article' => $article]) }}</loc><lastmod>{{ $article->updated_at->toAtomString() }}</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>@endforeach
@foreach($projects as $project)<url><loc>{{ route('projects.show', ['locale' => $locale, 'project' => $project]) }}</loc><lastmod>{{ $project->updated_at->toAtomString() }}</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>@endforeach
@endforeach
</urlset>
