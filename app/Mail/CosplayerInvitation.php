<?php

namespace App\Mail;

use App\Models\Cosplayer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CosplayerInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Cosplayer
     */
    public $cosplayer;

    /**
     * @var string
     */
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Cosplayer $cosplayer, string $message)
    {
        $this->cosplayer = $cosplayer;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
