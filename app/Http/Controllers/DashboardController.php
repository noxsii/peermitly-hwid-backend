<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Dashboard\GetDashboardStatsAction;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController
{
    public function index(GetDashboardStatsAction $getStats): Response
    {
        return Inertia::render('Dashboard', [
            'stats' => Inertia::defer(static fn (): array => $getStats->handle()->toArray()),
        ]);
    }
}
