<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

final class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(...),
                'subscription' => fn (): ?array => $this->subscriptionPayload($request->user()),
            ],
            'notifications' => fn (): array => $this->notificationPayload($request->user()),
            'flash' => fn (): array => [
                'status' => $request->session()->get('status'),
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }

    /**
     * The user's currently active subscription, shared on every request so the
     * frontend can gate access. Null when there is none.
     *
     * @return array{plan: string, status: string, ends_at: string, days_remaining: int, is_lifetime: bool}|null
     */
    private function subscriptionPayload(?User $user): ?array
    {
        $subscription = $user instanceof User ? $user->activeSubscription : null;

        if (! $subscription instanceof Subscription) {
            return null;
        }

        return [
            'plan' => $subscription->plan->label(),
            'status' => $subscription->status->value,
            'ends_at' => $subscription->ends_at->toIso8601String(),
            'days_remaining' => max(0, (int) round(today()->diffInDays($subscription->ends_at->copy()->startOfDay()))),
            'is_lifetime' => $subscription->plan->isLifetime(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function notificationPayload(?User $user): array
    {
        if (! $user instanceof User) {
            return ['items' => [], 'unread_count' => 0];
        }

        $items = $user->notifications()
            ->latest()
            ->limit(20)
            ->get()
            ->map(static fn ($notification): array => [
                'id' => $notification->id,
                'type' => $notification->type,
                'data' => $notification->data,
                'read_at' => $notification->read_at?->toIso8601String(),
                'created_at' => $notification->created_at?->toIso8601String(),
            ])
            ->all();

        return [
            'items' => $items,
            'unread_count' => $user->unreadNotifications()->count(),
        ];
    }
}
