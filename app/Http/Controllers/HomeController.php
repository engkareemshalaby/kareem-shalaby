<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $categories = Category::visible()->withCount(['articles' => fn ($query) => $query->published()])->get();
        $featured = Article::published()->with('category')->where('is_featured', true)->latest('published_at')->first();
        $articles = Article::published()->with('category')->latest('published_at')->paginate(9);

        return view('home', compact('categories', 'featured', 'articles'));
    }
}
