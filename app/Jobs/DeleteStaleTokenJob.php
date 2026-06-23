<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Tokens\DeleteStaleTokenAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Laravel\Sanctum\PersonalAccessToken;

final class DeleteStaleTokenJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly PersonalAccessToken $token) {}

    public function handle(DeleteStaleTokenAction $action): void
    {
        $action->handle($this->token);
    }
}
