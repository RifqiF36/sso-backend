<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerificationOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $userName;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $userName = null)
    {
        Log::info('Creating VerificationOtp email with OTP: ' . $otp . ' for user: ' . $userName);
        $this->otp = $otp;
        $this->userName = $userName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Verification OTP',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        Log::info('Preparing email with OTP: ' . $this->otp . ' for user: ' . $this->userName);
        return new Content(
            view: 'emails.verification-otp',
            with: [
                'otp' => $this->otp,
                'userName' => $this->userName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
