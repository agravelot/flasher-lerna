<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactSent extends Notification
{
    use Queueable;
    /**
     * @var Contact
     */
    private $contact;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->replyTo($this->contact->email)
            ->line("From : {$this->contact->name} <{$this->contact->email}>")
            ->line('Message :')
            ->line($this->contact->message)
            ->action(__('Read more'), url('/admin/contacts'));
    }
}
