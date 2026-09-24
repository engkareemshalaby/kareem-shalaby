<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSocialLinkRequest;
use App\Http\Requests\Admin\UpdateSocialLinkRequest;
use App\Models\SocialLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SocialLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.social-links.index', ['socialLinks' => SocialLink::orderBy('sort_order')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.social-links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocialLinkRequest $request): RedirectResponse
    {
        SocialLink::create([...$request->validated(), 'is_visible' => $request->boolean('is_visible')]);

        return redirect()->route('admin.social-links.index')->with('success', 'تمت إضافة وسيلة التواصل.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialLink $socialLink): RedirectResponse
    {
        return redirect()->route('admin.social-links.edit', $socialLink);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialLink $socialLink): View
    {
        return view('admin.social-links.edit', compact('socialLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocialLinkRequest $request, SocialLink $socialLink): RedirectResponse
    {
        $socialLink->update([...$request->validated(), 'is_visible' => $request->boolean('is_visible')]);

        return back()->with('success', 'تم تحديث وسيلة التواصل.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialLink $socialLink): RedirectResponse
    {
        $socialLink->delete();

        return redirect()->route('admin.social-links.index')->with('success', 'تم حذف وسيلة التواصل.');
    }
}
