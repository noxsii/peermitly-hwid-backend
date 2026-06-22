<?php

declare(strict_types=1);

namespace App\Http\Controllers\LicenseKeys;

use App\Actions\LicenseKeys\NormalizeLicenseKeyAction;
use App\Models\LicenseKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LicenseKeySearchController
{
    public function search(Request $request, NormalizeLicenseKeyAction $normalize): JsonResponse
    {
        $teamId = (int) auth()->user()?->current_team_id;
        $query = mb_trim($request->string('q')->toString());

        if (mb_strlen($query) < 2) {
            return new JsonResponse(['results' => []]);
        }

        $normalized = $normalize->handle($query, false);
        $like = '%'.$query.'%';

        $scoutIds = LicenseKey::search($query)
            ->keys()
            ->take(50)
            ->all();

        $results = LicenseKey::query()
            ->where('team_id', $teamId)
            ->where(function ($q) use ($scoutIds, $normalized, $like): void {
                $q->whereIn('id', $scoutIds)
                    ->orWhere('normalized_key', 'like', "%{$normalized}%")
                    ->orWhere('key', 'ilike', $like)
                    ->orWhereHas('product', fn ($p) => $p->where('name', 'ilike', $like)->orWhere('slug', 'ilike', $like))
                    ->orWhereHas('customer', fn ($c) => $c->where('email', 'ilike', $like)->orWhere('name', 'ilike', $like));
            })
            ->with(['product:id,name,slug', 'customer:id,email'])
            ->latest()
            ->limit(10)
            ->get(['id', 'uuid', 'key', 'status', 'product_id', 'customer_id', 'expires_at']);

        return new JsonResponse([
            'results' => $results->map(static fn (LicenseKey $key): array => [
                'uuid' => $key->uuid,
                'key' => $key->key,
                'status' => $key->status->value,
                'product' => $key->product->name,
                'customer' => $key->customer?->email,
                'expires_at' => $key->expires_at?->toIso8601String(),
            ])->all(),
        ]);
    }
}
