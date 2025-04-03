<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Deal;
use App\Models\User;

class DealRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deal;
    public $user;
    public $broker;

    public function __construct(Deal $deal, User $user, User $broker)
    {
        $this->deal = $deal;
        $this->user = $user;
        $this->broker = $broker;
    }

    public function build()
    {
        return $this->subject('New Deal Access Request')
                    ->view('emails.deal-request');
    }
}
