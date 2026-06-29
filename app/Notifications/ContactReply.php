<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactReply extends Notification
{
    use Queueable;

    public function __construct(
        public ContactMessage $message,
        public string $replyBody,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $siteName = setting('site_name', 'Kurnia Fedora');

        $subject = $this->message->subject
            ? 'Re: '.$this->message->subject
            : 'Balasan dari '.$siteName;

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Halo '.$this->message->name.',');

        // Tiap baris pesan dirender melalui Blade ({{ }}) sehingga aman dari HTML/XSS.
        foreach (preg_split('/\n{2,}/', trim($this->replyBody)) as $paragraph) {
            $mail->line(str_replace("\n", ' ', trim($paragraph)));
        }

        // Agar pengunjung bisa membalas langsung ke email perusahaan.
        if ($replyTo = setting('contact_email')) {
            $mail->replyTo($replyTo, $siteName);
        }

        return $mail->salutation('Salam, '.$siteName);
    }
}
