<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactSent extends Mailable implements ShouldQueue
{
    use Queueable,SerializesModels;
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
     * Get the mail representation of the notification.
     */
    public function build(): self
    {
        return $this->markdown('emails.contacts.contact')
                ->text('emails.contacts.contact_txt')
                ->subject('Nouveau Contact - Jkanda')
                ->with([
                    'contact' => $this->contact,
                ]);
    }
}
