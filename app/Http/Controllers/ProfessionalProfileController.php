<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfessionalProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $professionalCategories = Category::visible()->where('is_professional', true)->withCount(['articles' => fn ($query) => $query->published()])->get();
        $professionalArticles = Article::published()->with('category')->whereHas('category', fn ($query) => $query->where('is_professional', true))->latest('published_at')->limit(6)->get();
        $experiences = Experience::query()->where('is_visible', true)->orderBy('sort_order')->get();
        $settings = SiteSetting::query()->pluck('value', 'key');
        $projects = Project::visible()->limit(6)->get();

        return view('professional', compact('professionalCategories', 'professionalArticles', 'experiences', 'settings', 'projects'));
    }
}
