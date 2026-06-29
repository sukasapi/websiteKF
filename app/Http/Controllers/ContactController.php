<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Notifications\ContactAutoReply;
use App\Notifications\NewContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            // not_regex CR/LF mencegah header-injection pada field satu baris.
            'name'    => ['required', 'string', 'max:255', 'not_regex:/[\r\n]/'],
            'email'   => ['required', 'email:rfc', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255', 'not_regex:/[\r\n]/'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // Normalisasi: rapikan spasi, samakan email ke huruf kecil.
        $data['name']    = trim(preg_replace('/\s+/', ' ', $data['name']));
        $data['email']   = mb_strtolower(trim($data['email']));
        $data['subject'] = isset($data['subject']) ? trim($data['subject']) : null;
        $data['message'] = trim($data['message']);

        $message = ContactMessage::create($data);

        // Kirim notifikasi email ke admin (jika dikonfigurasi).
        // Bungkus dengan try/catch agar kegagalan SMTP tidak menggagalkan submit form.
        try {
            if ($adminEmail = setting('contact_email')) {
                Notification::route('mail', $adminEmail)->notify(new NewContactMessage($message));
            }

            // Balasan otomatis ke pengirim (jika diaktifkan di pengaturan).
            if (setting('contact_autoreply_enabled')) {
                Notification::route('mail', $message->email)->notify(new ContactAutoReply($message));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email kontak: '.$e->getMessage());
        }

        return back()->with('success', __('messages.contact_success'));
    }
}
