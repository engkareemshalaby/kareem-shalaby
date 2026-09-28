<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.contact-messages.index', [
            'messages' => ContactMessage::query()->latest()->paginate(25),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $contactMessage): View
    {
        if ($contactMessage->read_at === null) {
            $contactMessage->update(['read_at' => now()]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', 'تم حذف الرسالة.');
    }
}
