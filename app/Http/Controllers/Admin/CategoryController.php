<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.categories.index', ['categories' => Category::withCount('articles')->orderBy('sort_order')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($this->payload($request->validated(), $request));

        return redirect()->route('admin.categories.index')->with('success', 'تمت إضافة القسم.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): RedirectResponse
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($this->payload($request->validated(), $request));

        return back()->with('success', 'تم تحديث القسم.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->articles()->exists()) {
            return back()->withErrors(['category' => 'لا يمكن حذف قسم يحتوي على مقالات. يمكنك إخفاؤه بدلًا من ذلك.']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'تم حذف القسم.');
    }

    /** @param array<string, mixed> $validated */
    private function payload(array $validated, Request $request): array
    {
        $validated['is_professional'] = $request->boolean('is_professional');
        $validated['is_visible'] = $request->boolean('is_visible');

        return $validated;
    }
}
