<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $verificationUrl = route('verify.email', ['token' => $this->user->verification_token]);

        return $this->subject('Verify Your Email Address')
            ->view('emails.verify-email')
            ->with(['verificationUrl' => $verificationUrl, 'user' => $this->user]);
    }
}
