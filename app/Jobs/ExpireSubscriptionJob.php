<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Subscriptions\ExpireSubscriptionAction;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class ExpireSubscriptionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Subscription $subscription) {}

    public function handle(ExpireSubscriptionAction $action): void
    {
        $action->handle($this->subscription);
    }
}
