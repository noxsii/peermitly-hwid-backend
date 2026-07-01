<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Users\DeactivateUnsubscribedUserAction;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class EnforceUserSubscriptionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly User $user) {}

    public function handle(DeactivateUnsubscribedUserAction $action): void
    {
        $action->handle($this->user);
    }
}
