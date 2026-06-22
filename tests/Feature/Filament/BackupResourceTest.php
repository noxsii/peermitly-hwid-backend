<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Filament\Resources\Backups\Pages\ListBackups;
use App\Models\Backup;
use App\Models\User;
use Livewire\Livewire;

test('the backups list shows received backups', function (): void {
    $this->actingAs(User::factory()->create(['role' => UserRole::SUPER_ADMIN]));

    $backup = Backup::factory()->for(User::factory())->create();

    Livewire::test(ListBackups::class)
        ->assertCanSeeTableRecords([$backup])
        ->assertCanRenderTableColumn('machine_guid')
        ->assertCanRenderTableColumn('disks');
});
