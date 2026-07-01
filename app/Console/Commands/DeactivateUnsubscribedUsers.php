<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Jobs\EnforceUserSubscriptionJob;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

#[Description('Dispatch a job per active user to deactivate anyone without an active subscription.')]
#[Signature('users:enforce-subscription')]
final class DeactivateUnsubscribedUsers extends Command
{
    public function handle(): int
    {
        $count = 0;

        User::query()
            ->where('is_active', true)
            ->where('role', UserRole::USER->value)
            ->chunkById(500, function (Collection $users) use (&$count): void {
                /** @var User $user */
                foreach ($users as $user) {
                    dispatch(new EnforceUserSubscriptionJob($user));
                    $count++;
                }
            });

        $this->info("Dispatched {$count} subscription enforcement job(s).");

        return self::SUCCESS;
    }
}
