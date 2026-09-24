<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $stats = [
            'articles' => Article::count(),
            'published' => Article::published()->count(),
            'visits_today' => Visit::where('is_bot', false)->whereDate('created_at', today())->count(),
            'unique_today' => Visit::where('is_bot', false)->whereDate('created_at', today())->distinct('ip_hash')->count('ip_hash'),
        ];
        $topPages = Visit::query()->where('is_bot', false)->where('created_at', '>=', now()->subDays(30))->selectRaw('path, COUNT(*) as visits')->groupBy('path')->orderByDesc('visits')->limit(8)->get();
        $recentArticles = Article::with('category')->latest()->limit(6)->get();

        return view('admin.dashboard', compact('stats', 'topPages', 'recentArticles'));
    }
}
