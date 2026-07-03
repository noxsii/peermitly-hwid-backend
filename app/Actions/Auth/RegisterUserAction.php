<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Subscriptions\GrantSubscriptionAction;
use App\Enums\SubscriptionPlan;
use App\Enums\UserRole;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final readonly class RegisterUserAction
{
    public function __construct(private GrantSubscriptionAction $grantSubscription) {}

    public function handle(string $name, string $email, string $password): User
    {
        $user = DB::transaction(function () use ($name, $email, $password): User {
            $user = User::query()->create([
                'name' => $name,
                'email' => mb_strtolower($email),
                'password' => Hash::make($password),
                'role' => UserRole::USER,
                'is_active' => false,
                'email_verified_at' => null,
            ]);

            $this->grantSubscription->handle($user, SubscriptionPlan::FREE);

            return $user;
        });

        $user->notify(new VerifyEmailNotification());

        return $user;
    }
}
