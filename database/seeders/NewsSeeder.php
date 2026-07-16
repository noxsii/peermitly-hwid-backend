<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

final class NewsSeeder extends Seeder
{
    public function run(): void
    {
        if (News::query()->exists()) {
            return;
        }

        News::factory()
            ->showcase()
            ->publishedAt(now()->subDays(1))
            ->create([
                'slug' => 'hwid-spoofer-v2',
                'title' => 'HWID Spoofer v2 — a full rewrite',
                'description' => 'Per-profile hardware identities, one-click switching and a public API. Everything that shipped in the biggest release yet.',
            ]);

        News::factory()
            ->withoutImage()
            ->publishedAt(now()->subDays(6))
            ->create([
                'slug' => 'dashboard-refresh',
                'title' => 'A calmer, faster dashboard',
                'description' => 'We rebuilt the dashboard around your profiles so the thing you use most is the thing you see first.',
            ]);

        News::factory()
            ->withoutImage()
            ->publishedAt(now()->subDays(20))
            ->create([
                'slug' => 'status-page-launch',
                'title' => 'Live status page is here',
                'description' => 'Real-time uptime and incident history for every Peermitly service, now public.',
            ]);

        News::factory()
            ->unpublished()
            ->withoutImage()
            ->create([
                'slug' => 'coming-soon-teams',
                'title' => 'Teams (coming soon)',
                'description' => 'Draft — not visible on the public site yet.',
            ]);
    }
}
