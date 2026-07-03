<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\URL;

#[Queue('mail')]
final class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public const int EXPIRES_IN_HOURS = 3;

    /**
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Date::now()->addHours(self::EXPIRES_IN_HOURS),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ],
        );

        return new MailMessage()
            ->subject('Confirm your Peermitly email')
            ->view('emails.auth.verify-email', [
                'userName' => $notifiable->name,
                'verifyUrl' => $verifyUrl,
                'expiresInHours' => self::EXPIRES_IN_HOURS,
                'requestedAt' => Date::now(),
            ]);
    }
}
