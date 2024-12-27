<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deal;
    public $link;

    /**
     * Create a new message instance.
     */
    public function __construct($deal, $link)
    {
        $this->deal = $deal;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('You’re Invited to Collaborate on a Deal')
            ->view('emails.deal-invitation')
            ->with([
                'dealTitle' => $this->deal->name,
                'dealDescription' => $this->deal->description,
                'link' => $this->link,
            ]);
    }
}
