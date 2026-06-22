<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ExpireSubscriptionJob;
use App\Models\Subscription;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

#[Description('Expire subscriptions whose term has elapsed.')]
#[Signature('subscriptions:expire')]
final class ExpireSubscriptions extends Command
{
    public function handle(): int
    {
        $count = 0;

        Subscription::query()->chunkById(200, function (Collection $subscriptions) use (&$count): void {
            /** @var Subscription $subscription */
            foreach ($subscriptions as $subscription) {
                dispatch(new ExpireSubscriptionJob($subscription));
                $count++;
            }
        });

        $this->info("Dispatched {$count} subscription expiry job(s).");

        return self::SUCCESS;
    }
}
