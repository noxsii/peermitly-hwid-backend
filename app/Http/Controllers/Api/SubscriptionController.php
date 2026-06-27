<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\SubscriptionResource;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function assert;

final class SubscriptionController
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        assert($user instanceof User);

        $subscription = $user->activeSubscription;
        $isValid = $user->is_active && $subscription instanceof Subscription;

        return response()->json([
            'valid' => $isValid,
            'is_active' => $user->is_active,
            'subscription' => $subscription instanceof Subscription
                ? new SubscriptionResource($subscription)
                : null,
        ]);
    }
}
