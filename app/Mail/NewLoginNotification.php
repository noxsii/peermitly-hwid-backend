<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Queue\SerializesModels;

#[Queue('mail')]
final class NewLoginNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $ipAddress,
        public readonly string $userAgent,
        public readonly CarbonInterface $loggedInAt,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [new Address($this->user->email, $this->user->name ?? '')],
            subject: 'New sign-in to your Peermitly account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.new-login',
            with: [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'ipAddress' => $this->ipAddress,
                'userAgent' => $this->userAgent,
                'loggedInAt' => $this->loggedInAt,
            ],
        );
    }
}
