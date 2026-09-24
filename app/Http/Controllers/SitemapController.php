<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $articles = Article::published()->latest('updated_at')->get(['slug', 'updated_at']);
        $projects = Project::visible()->get(['slug', 'updated_at']);

        return response()->view('sitemap', compact('articles', 'projects'))->header('Content-Type', 'application/xml');
    }
}
