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

#[Queue('mail')]
final class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly string $token) {}

    /**
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $expiresInMinutes = (int) config('auth.passwords.users.expire', 60);
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], absolute: false));

        return new MailMessage()
            ->subject('Reset your Peermitly password')
            ->view('emails.auth.reset-password', [
                'userName' => $notifiable->name,
                'resetUrl' => $resetUrl,
                'expiresInMinutes' => $expiresInMinutes,
                'requestedAt' => Date::now(),
            ]);
    }
}
