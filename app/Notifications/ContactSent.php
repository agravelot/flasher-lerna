<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContactSent extends Notification
{
    use Queueable;
    /**
     * @var Contact
     */
    private $contact;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Get the notification's delivery channels.
     *
     *
     * @return array
     */
    public function via($notifiable)
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
            ->line('From :'.$this->contact->name)
            ->line('Message :')
            ->line($this->contact->message)
            ->action(__('Read More'), url('/admin/contacts'));
    }
}
