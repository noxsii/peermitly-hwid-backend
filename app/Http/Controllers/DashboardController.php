<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Dashboard\GetDashboardStatsAction;
use App\Http\Resources\ApiRequestLogResource;
use App\Http\Resources\LicenseKeys\LicenseKeyResource;
use App\Http\Resources\UserResource;
use App\Models\ApiRequestLog;
use App\Models\LicenseKey;
use App\Models\Team;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController
{
    public function index(GetDashboardStatsAction $getStats): Response
    {
        $teamId = (int) auth()->user()?->current_team_id;

        return Inertia::render('Dashboard', [
            'stats' => Inertia::defer(static fn (): array => $getStats->handle($teamId)->toArray()),

            'recentLicenseKeys' => Inertia::defer(static fn () => LicenseKeyResource::collection(
                LicenseKey::query()
                    ->where('team_id', $teamId)
                    ->with(['type', 'product', 'customer'])
                    ->latest()
                    ->limit(8)
                    ->get(),
            )),

            'teamMembers' => Inertia::defer(static function () use ($teamId) {
                $team = Team::query()->find($teamId);
                if ($team === null) {
                    return UserResource::collection(collect());
                }

                return UserResource::collection(
                    $team->users()->orderBy('name')->limit(8)->get(),
                );
            }),

            'recentApiCalls' => Inertia::defer(static function () use ($teamId) {
                $team = Team::query()->find($teamId);
                $userIds = $team !== null
                    ? $team->users()->pluck('users.id')->all()
                    : [];

                $teamKeyIds = LicenseKey::query()
                    ->where('team_id', $teamId)
                    ->pluck('id')
                    ->all();

                return ApiRequestLogResource::collection(
                    ApiRequestLog::query()
                        ->where(function ($q) use ($userIds, $teamKeyIds): void {
                            $q->whereIn('user_id', $userIds === [] ? [0] : $userIds)
                                ->orWhereIn('license_key_id', $teamKeyIds === [] ? [0] : $teamKeyIds);
                        })
                        ->latest()
                        ->limit(8)
                        ->get(),
                );
            }),
        ]);
    }
}
