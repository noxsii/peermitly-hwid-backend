<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Subscriptions\ExpireSubscriptionsAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class ExpireSubscriptionsJob implements ShouldQueue
{
    use Queueable;

    public function handle(ExpireSubscriptionsAction $action): void
    {
        $action->handle();
    }
}
