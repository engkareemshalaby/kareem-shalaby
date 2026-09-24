<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.projects.index', ['projects' => Project::orderBy('sort_order')->latest()->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = Project::create($this->payload($request->validated(), $request));

        return redirect()->route('admin.projects.edit', $project)->with('success', 'تمت إضافة المشروع.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): RedirectResponse
    {
        return redirect()->route('admin.projects.edit', $project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $validated = $request->validated();
        $removedScreenshots = array_values(array_intersect($project->screenshots ?? [], $validated['remove_screenshots'] ?? []));
        Storage::disk('public')->delete($removedScreenshots);

        if ($request->hasFile('cover_image') && $project->cover_image) {
            Storage::disk('public')->delete($project->cover_image);
        }

        $project->update($this->payload($validated, $request, $project, $removedScreenshots));

        return back()->with('success', 'تم تحديث المشروع.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        Storage::disk('public')->delete(array_filter([$project->cover_image, ...($project->screenshots ?? [])]));
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'تم حذف المشروع وصوره.');
    }

    /** @param array<string, mixed> $validated */
    private function payload(array $validated, Request $request, ?Project $project = null, array $removedScreenshots = []): array
    {
        unset($validated['cover_image'], $validated['screenshots'], $validated['remove_screenshots']);
        $validated['technologies'] = collect(explode(',', $validated['technologies'] ?? ''))->map(fn (string $technology) => trim($technology))->filter()->unique()->values()->all();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_visible'] = $request->boolean('is_visible');

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('projects/covers', 'public');
        }

        $screenshots = collect($project?->screenshots ?? [])->reject(fn (string $path) => in_array($path, $removedScreenshots, true));
        foreach ($request->file('screenshots', []) as $screenshot) {
            $screenshots->push($screenshot->store('projects/screenshots', 'public'));
        }
        $validated['screenshots'] = $screenshots->values()->all();

        return $validated;
    }
}
