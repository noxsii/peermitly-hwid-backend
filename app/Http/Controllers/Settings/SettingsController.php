<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelPasskeys\Models\Passkey;

final class SettingsController
{
    public function edit(): Response
    {
        $userId = (int) auth()->id();

        return Inertia::render('Settings', [
            'passkeys' => Inertia::defer(static fn (): array => Passkey::query()
                ->where('authenticatable_id', $userId)
                ->latest()
                ->get()
                ->map(static fn (Passkey $passkey): array => [
                    'id' => (int) $passkey->getAttribute('id'),
                    'name' => (string) $passkey->getAttribute('name'),
                    'created_at' => $passkey->created_at?->toIso8601String(),
                    'last_used_at' => $passkey->last_used_at?->toIso8601String(),
                ])
                ->all()),
        ]);
    }
}
