<?php

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
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line(__('A new album in which you appear has been published.'))
                    ->action(__('Show album'), url(route('albums.show', $this->album)))
                    ->line(__('If this album is not accessible, make sure to authenticate to your account.'))
                    ->action(__('Login'), url(route('login')));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
