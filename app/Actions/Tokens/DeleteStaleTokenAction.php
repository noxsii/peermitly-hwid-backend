<?php

declare(strict_types=1);

namespace App\Actions\Tokens;

use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

final readonly class DeleteStaleTokenAction
{
    /**
     * Delete a personal access token when it has never been used or its last
     * usage is older than three days. Returns true when the token was deleted.
     */
    public function handle(PersonalAccessToken $token): bool
    {
        if ($token->last_used_at !== null && $token->last_used_at->greaterThan(Carbon::now()->subDays(3))) {
            return false;
        }

        $token->delete();

        return true;
    }
}
