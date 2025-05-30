<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\DealRequest;

class InformBuyerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dealRequest;

    public function __construct($dealRequest)
    {
        $this->dealRequest = $dealRequest;
    }

    public function build()
    {
        return $this->subject('Your Deal Request Has Been Approved')
            ->view('emails.deal-approved');
    }
}
