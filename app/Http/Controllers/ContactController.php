<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Notifications\NewContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function index()
    {
        return view('site.contact');
    }

    public function store(Request $request)
    {
        // Honeypot anti-spam: field "website" harus kosong.
        if ($request->filled('website')) {
            return back();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = ContactMessage::create($data);

        // Kirim notifikasi email ke admin (jika dikonfigurasi).
        if ($adminEmail = setting('contact_email')) {
            Notification::route('mail', $adminEmail)->notify(new NewContactMessage($message));
        }

        return back()->with('success', __('messages.contact_success'));
    }
}
