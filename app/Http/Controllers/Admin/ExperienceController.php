<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExperienceRequest;
use App\Http\Requests\Admin\UpdateExperienceRequest;
use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.experiences.index', ['experiences' => Experience::orderBy('sort_order')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.experiences.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExperienceRequest $request): RedirectResponse
    {
        Experience::create($this->payload($request->validated(), $request));

        return redirect()->route('admin.experiences.index')->with('success', 'تمت إضافة الخبرة.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Experience $experience): RedirectResponse
    {
        return redirect()->route('admin.experiences.edit', $experience);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Experience $experience): View
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExperienceRequest $request, Experience $experience): RedirectResponse
    {
        $experience->update($this->payload($request->validated(), $request));

        return back()->with('success', 'تم تحديث الخبرة.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        return redirect()->route('admin.experiences.index')->with('success', 'تم حذف الخبرة.');
    }

    /** @param array<string, mixed> $validated */
    private function payload(array $validated, Request $request): array
    {
        $validated['is_visible'] = $request->boolean('is_visible');
        $validated['highlights'] = collect(preg_split('/\r\n|\r|\n/', $validated['highlights']))->map(fn (string $item) => trim($item))->filter()->values()->all();

        return $validated;
    }
}
