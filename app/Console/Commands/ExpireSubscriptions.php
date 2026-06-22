<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ExpireSubscriptionsJob;
use Illuminate\Console\Command;

final class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Expire subscriptions whose term has elapsed.';

    public function handle(): int
    {
        ExpireSubscriptionsJob::dispatch();

        $this->info('Subscription expiry job dispatched.');

        return self::SUCCESS;
    }
}
