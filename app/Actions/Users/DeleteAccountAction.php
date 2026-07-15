<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Throwable;

final class DeleteAccountAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user): void
    {
        DB::transaction(static function () use ($user): void {
            $user->notifications()->delete();

            Activity::query()
                ->where(static function ($query) use ($user): void {
                    $query->where('subject_type', $user->getMorphClass())
                        ->where('subject_id', $user->getKey());
                })
                ->orWhere(static function ($query) use ($user): void {
                    $query->where('causer_type', $user->getMorphClass())
                        ->where('causer_id', $user->getKey());
                })
                ->delete();

            $user->passkeys()->delete();
            $user->tokens()->delete();
            $user->subscriptions()->delete();

            $user->delete();
        });
    }
}
