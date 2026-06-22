<?php

declare(strict_types=1);

use App\Models\Backup;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function backupPayload(array $overrides = []): array
{
    return array_replace([
        'id' => 'backup-1700000000000',
        'created_at' => 1700000000000,
        'label' => 'before spoof',
        'file_size_bytes' => 2048,
        'snapshot' => [
            'machine_guid' => 'GUID-123',
            'disks' => [
                ['index' => 0, 'model' => 'Samsung SSD', 'serial_number' => 'X1'],
            ],
        ],
        'restore_point' => [
            'sequence' => 12,
            'created' => true,
            'message' => null,
            'size_bytes' => 500000,
        ],
    ], $overrides);
}

test('storing a backup requires authentication', function (): void {
    $this->postJson('/api/backups', backupPayload())->assertUnauthorized();
});

test('an authenticated client can store a backup', function (): void {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['spoofer:use']);

    $this->postJson('/api/backups', backupPayload())
        ->assertCreated()
        ->assertJsonPath('client_backup_id', 'backup-1700000000000')
        ->assertJsonPath('machine_guid', 'GUID-123')
        ->assertJsonPath('data.snapshot.disks.0.model', 'Samsung SSD');

    $backup = Backup::query()->where('client_backup_id', 'backup-1700000000000')->firstOrFail();

    expect($backup->user_id)->toBe($user->id)
        ->and($backup->label)->toBe('before spoof')
        ->and($backup->machine_guid)->toBe('GUID-123')
        ->and($backup->client_created_at?->getTimestampMs())->toBe(1700000000000)
        ->and($backup->data['snapshot']['disks'])->toHaveCount(1);
});

test('a single posted entry is stored as one object, never an array', function (): void {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['spoofer:use']);

    $this->postJson('/api/backups', backupPayload())->assertCreated();

    $backup = Backup::query()->firstOrFail();

    expect(array_is_list($backup->data))->toBeFalse()
        ->and($backup->data)->toHaveKey('id')
        ->and($backup->data['snapshot']['disks'])->toHaveCount(1)
        ->and(Backup::query()->count())->toBe(1);
});

test('unknown extensible fields are preserved in the json payload', function (): void {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['spoofer:use']);

    $payload = backupPayload();
    $payload['snapshot']['gpus'] = [['index' => 0, 'name' => 'RTX 4090']];
    $payload['network_adapters'] = [['mac' => 'AA:BB:CC']];

    $this->postJson('/api/backups', $payload)->assertCreated();

    $backup = Backup::query()->firstOrFail();

    expect($backup->data['snapshot']['gpus'][0]['name'])->toBe('RTX 4090')
        ->and($backup->data['network_adapters'][0]['mac'])->toBe('AA:BB:CC');
});

test('re-sending the same backup id updates instead of duplicating', function (): void {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['spoofer:use']);

    $this->postJson('/api/backups', backupPayload(['label' => 'first']))->assertCreated();
    $this->postJson('/api/backups', backupPayload(['label' => 'second']))->assertOk();

    expect(Backup::query()->where('user_id', $user->id)->count())->toBe(1)
        ->and(Backup::query()->where('user_id', $user->id)->value('label'))->toBe('second');
});

test('backup id is required', function (): void {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['spoofer:use']);

    $this->postJson('/api/backups', backupPayload(['id' => null]))
        ->assertStatus(422)
        ->assertJsonValidationErrors('id');
});

test('clients only see their own backups', function (): void {
    $user = User::factory()->create();
    Backup::factory()->count(2)->for($user)->create();
    Backup::factory()->for(User::factory())->create();

    Sanctum::actingAs($user, ['spoofer:use']);

    $this->getJson('/api/backups')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});
