<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Logging\StoreApiRequestLogAction;
use App\Data\Logging\ApiRequestLogData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class LogApiRequestJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly ApiRequestLogData $data) {}

    public function handle(StoreApiRequestLogAction $action): void
    {
        $action->handle($this->data);
    }
}
