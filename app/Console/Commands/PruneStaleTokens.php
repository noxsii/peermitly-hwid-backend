<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\DeleteStaleTokenJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;
use Laravel\Sanctum\PersonalAccessToken;

#[Description('Delete personal access tokens that are unused or older than three days.')]
#[Signature('tokens:prune')]
final class PruneStaleTokens extends Command
{
    public function handle(): int
    {
        $threshold = Date::now()->subDays(3);
        $count = 0;

        PersonalAccessToken::query()
            ->where(function ($query) use ($threshold): void {
                $query->whereNull('last_used_at')
                    ->orWhere('last_used_at', '<=', $threshold);
            })
            ->chunkById(200, function (Collection $tokens) use (&$count): void {
                /** @var PersonalAccessToken $token */
                foreach ($tokens as $token) {
                    dispatch(new DeleteStaleTokenJob($token));
                    $count++;
                }
            });

        $this->info("Dispatched {$count} stale token deletion job(s).");

        return self::SUCCESS;
    }
}
