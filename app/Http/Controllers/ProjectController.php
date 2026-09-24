<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::visible();

        if ($request->filled('type')) {
            $query->where('project_type', $request->string('type'));
        }

        if ($request->filled('system')) {
            $query->where('system_type', $request->string('system'));
        }

        return view('projects.index', [
            'projects' => $query->paginate(12)->withQueryString(),
            'projectTypes' => Project::visible()->whereNotNull('project_type')->distinct()->orderBy('project_type')->pluck('project_type'),
            'systemTypes' => Project::visible()->whereNotNull('system_type')->distinct()->orderBy('system_type')->pluck('system_type'),
        ]);
    }

    public function show(string $locale, Project $project): View
    {
        abort_unless($project->is_visible, 404);

        return view('projects.show', compact('project'));
    }
}
