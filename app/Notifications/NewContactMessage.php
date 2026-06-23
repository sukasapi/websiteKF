<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification
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
        return (new MailMessage)
            ->subject('Pesan Kontak Baru: '.($this->message->subject ?: '(tanpa subjek)'))
            ->greeting('Pesan kontak baru diterima')
            ->line('Nama: '.$this->message->name)
            ->line('Email: '.$this->message->email)
            ->line('Subjek: '.($this->message->subject ?: '-'))
            ->line('Pesan:')
            ->line($this->message->message)
            ->action('Buka Backoffice', url('/backoffice/contact-messages'));
    }
}
