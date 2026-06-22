<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController
{
    public function index(): Response
    {
        $user = auth()->user();

        return Inertia::render('Dashboard', [
            'subscription' => Inertia::defer(static function () use ($user): ?array {
                $subscription = $user instanceof User ? $user->activeSubscription : null;

                if (! $subscription instanceof Subscription) {
                    return null;
                }

                return [
                    'plan' => $subscription->plan->label(),
                    'status' => $subscription->status->value,
                    'ends_at' => $subscription->ends_at->toIso8601String(),
                    'days_remaining' => max(0, (int) ceil(now()->diffInDays($subscription->ends_at))),
                ];
            }),
        ]);
    }
}
