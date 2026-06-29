<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactAutoReply extends Notification
{
    use Queueable;

    public function __construct(public ContactMessage $message)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $siteName = setting('site_name', 'Kurnia Fedora');

        $subject = setting('contact_autoreply_subject')
            ?: 'Terima kasih telah menghubungi '.$siteName;

        $template = setting('contact_autoreply_message')
            ?: "Halo {name},\n\nTerima kasih telah menghubungi kami. Pesan Anda sudah kami terima dan tim kami akan segera menghubungi Anda kembali.";

        // Sisipkan nama pengirim ke placeholder {name}.
        $body = str_replace('{name}', $this->message->name, $template);

        $mail = (new MailMessage)->subject($subject);

        // Tiap baris menjadi paragraf agar tetap rapi pada template email bawaan.
        foreach (preg_split('/\n{2,}/', trim($body)) as $paragraph) {
            $mail->line(str_replace("\n", ' ', trim($paragraph)));
        }

        return $mail->salutation('Salam, '.$siteName);
    }
}
