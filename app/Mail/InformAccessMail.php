<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InformAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deal;

    /**
     * Create a new message instance.
     */
    public function __construct($deal)
    {
        $this->deal = $deal;
    }

    public function build()
    {
        //invitation mail data 
        
        return $this->subject('You’re Invited to Collaborate on a Deal')
            ->view('emails.inform-access')
            ->with([
                'dealTitle' => $this->deal->name,
                'dealDescription' => $this->deal->description
            ]);
    }
}
