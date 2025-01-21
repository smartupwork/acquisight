<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlertInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deal;
    public $link;
    public $seller_email;
    
    /**
     * Create a new message instance.
     */
    public function __construct($deal, $link, $seller_email)
    {
        $this->deal = $deal;
        $this->link = $link;
        $this->seller_email = $seller_email;
    }

    public function build()
    {
        //invitation mail data 
        
        return $this->subject('You’ve Invited Seller to Collaborate on a Deal')
            ->view('emails.broker-invitation')
            ->with([
                'dealTitle' => $this->deal->name,
                'dealDescription' => $this->deal->description,
                'link' => $this->link,
                'seller_email' => $this->seller_email,
            ]);
    }
}
