<?php

declare(strict_types=1);

use App\Mail\NewLoginNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('successful web login dispatches NewLoginNotification mail to the user', function (): void {
    Mail::fake();

    User::factory()->create([
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ]);

    $this->post('/login', [
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ])->assertRedirect();

    Mail::assertQueued(NewLoginNotification::class, fn (NewLoginNotification $mail): bool => $mail->hasTo('ada@example.com'));
});

test('failed login does not dispatch the notification', function (): void {
    Mail::fake();

    User::factory()->create([
        'email' => 'ada@example.com',
        'password' => 'correct-password',
    ]);

    $this->from('/login')
        ->post('/login', [
            'email' => 'ada@example.com',
            'password' => 'wrong-password',
        ])
        ->assertRedirect('/login');

    Mail::assertNothingQueued();
    Mail::assertNothingSent();
});

test('the rendered email contains the user email, ip address, and a sign-in heading', function (): void {
    $user = User::factory()->create([
        'email' => 'ada@example.com',
    ]);

    $mail = new NewLoginNotification(
        user: $user,
        ipAddress: '203.0.113.42',
        userAgent: 'TestRunner/1.0',
        loggedInAt: now(),
    );

    $rendered = $mail->render();

    expect($rendered)->toContain('ada@example.com')
        ->and($rendered)->toContain('203.0.113.42')
        ->and($rendered)->toContain('TestRunner/1.0')
        ->and($rendered)->toContain('New sign-in to your Peermitly account');
});
