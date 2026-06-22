<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Changelog;
use Illuminate\Database\Seeder;

final class ChangelogSeeder extends Seeder
{
    public function run(): void
    {
        if (Changelog::query()->exists()) {
            return;
        }

        Changelog::factory()
            ->version('v0.2.0')
            ->publishedAt(now()->subDays(2))
            ->create(['title' => 'Spoof profiles and dashboard']);

        Changelog::factory()
            ->version('v0.1.0')
            ->publishedAt(now()->subDays(14))
            ->create(['title' => 'Initial HWID spoofer backend']);

        Changelog::factory()
            ->unpublished()
            ->create(['title' => 'Drafted but not yet released']);
    }
}
