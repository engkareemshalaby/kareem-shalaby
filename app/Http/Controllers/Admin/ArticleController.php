<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.articles.index', ['articles' => Article::with('category')->latest()->paginate(20)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.articles.create', [
            'categories' => Category::orderBy('sort_order')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $article = Article::create($this->payload($validated, $request));
        $article->tags()->sync($this->tagIds($validated));

        return redirect()->route('admin.articles.edit', $article)->with('success', 'تم حفظ المقال بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article): RedirectResponse
    {
        return redirect()->route('admin.articles.edit', $article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article): View
    {
        return view('admin.articles.edit', [
            'article' => $article->load('tags'),
            'categories' => Category::orderBy('sort_order')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image') && $article->cover_image) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->update($this->payload($validated, $request));
        $article->tags()->sync($this->tagIds($validated));

        return back()->with('success', 'تم تحديث المقال.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        if ($article->cover_image) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'تم حذف المقال.');
    }

    /** @param array<string, mixed> $validated */
    private function payload(array $validated, Request $request): array
    {
        unset($validated['tags'], $validated['new_tag_name'], $validated['new_tag_slug'], $validated['cover_image']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['published_at'] = $validated['status'] === 'published' ? ($validated['published_at'] ?? now()) : null;
        $validated['seo_keywords'] = collect(explode(',', $validated['seo_keywords'] ?? ''))->map(fn (string $keyword) => trim($keyword))->filter()->values()->all();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('articles/covers', 'public');
        }

        return $validated;
    }

    /** @param array<string, mixed> $validated */
    private function tagIds(array $validated): array
    {
        $tagIds = $validated['tags'] ?? [];

        if (! empty($validated['new_tag_name']) && ! empty($validated['new_tag_slug'])) {
            $tagIds[] = Tag::create([
                'name' => $validated['new_tag_name'],
                'slug' => $validated['new_tag_slug'],
            ])->getKey();
        }

        return array_values(array_unique($tagIds));
    }
}
