<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::published()->with(['category', 'tags'])->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $request->string('category')));
        }

        if ($request->filled('q')) {
            $term = '%'.$request->string('q')->trim().'%';
            $query->where(fn ($articleQuery) => $articleQuery->where('title_ar', 'like', $term)->orWhere('title_en', 'like', $term));
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn ($tagQuery) => $tagQuery->where('slug', $request->string('tag')));
        }

        return view('articles.index', [
            'articles' => $query->paginate(12)->withQueryString(),
            'categories' => Category::visible()->get(),
            'tags' => Tag::whereHas('articles', fn ($articleQuery) => $articleQuery->published())->orderBy('name')->get(),
        ]);
    }

    public function show(string $locale, Article $article): View
    {
        abort_unless($article->status === 'published' && $article->published_at?->isPast(), 404);

        $article->load(['category', 'tags']);
        $related = Article::published()->with(['category', 'tags'])->whereBelongsTo($article->category)->whereKeyNot($article->getKey())->latest('published_at')->limit(3)->get();

        return view('articles.show', compact('article', 'related'));
    }
}
