<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', ['settings' => SiteSetting::query()->pluck('value', 'key')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'phone_secondary' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:150'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:150'],
            'professional_summary' => ['required', 'string', 'max:2000'],
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value, 'type' => $key === 'professional_summary' ? 'textarea' : 'text']);
        }

        return back()->with('success', 'تم تحديث بيانات الملف المهني والتواصل.');
    }
}
