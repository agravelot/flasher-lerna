<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Album;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PublishedAlbum extends Notification
{
    use Queueable;
    /**
     * @var Album
     */
    private $album;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Album $album)
    {
        $this->album = $album;
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
                    ->line(__('New photos of you have just been published.'))
                    ->action(__('Show my pictures'), url(route('albums.show', ['album', $this->album])))
                    ->line(__('If this album is not accessible, make sure to authenticate to your account.'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
