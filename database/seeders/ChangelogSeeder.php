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
            ->version('v1.4.0')
            ->publishedAt(now()->subDays(2))
            ->create(['title' => 'Customer CRUD and Changelog']);

        Changelog::factory()
            ->version('v1.3.0')
            ->publishedAt(now()->subDays(14))
            ->create(['title' => 'Landing page and sidebar refresh']);

        Changelog::factory()
            ->version('v1.2.0')
            ->publishedAt(now()->subDays(30))
            ->create(['title' => 'API request logging and Filament admin']);

        Changelog::factory()
            ->unpublished()
            ->create(['title' => 'Drafted but not yet released']);
    }
}
