<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Backup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Backup>
 */
final class BackupFactory extends Factory
{
    /**
     * @var class-string<Backup>
     */
    protected $model = Backup::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $machineGuid = fake()->uuid();
        $createdAtMs = fake()->dateTimeBetween('-30 days')->getTimestamp() * 1000;

        return [
            'user_id' => User::factory(),
            'client_backup_id' => 'backup-'.$createdAtMs,
            'label' => fake()->optional()->words(2, true),
            'machine_guid' => $machineGuid,
            'client_created_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'data' => [
                'id' => 'backup-'.$createdAtMs,
                'created_at' => $createdAtMs,
                'label' => 'peermitly backup',
                'file_size_bytes' => fake()->numberBetween(1000, 50000),
                'snapshot' => [
                    'machine_guid' => $machineGuid,
                    'disks' => [
                        [
                            'index' => 0,
                            'vendor' => 'Samsung',
                            'product' => 'SSD 980 PRO 2TB',
                            'firmware_revision' => '5B2QGXA7',
                            'serial_number' => fake()->bothify('S#?#?#?#?#'),
                            'bus_type' => 'NVMe',
                            'removable' => false,
                            'partition_style' => 'GPT',
                            'mbr_signature' => null,
                            'gpt_disk_id' => fake()->uuid(),
                            'nvme_eui64' => fake()->bothify('################'),
                            'nvme_nguid' => fake()->bothify('################################'),
                            'device_ids' => [],
                        ],
                    ],
                    'volumes' => [
                        [
                            'mount_point' => 'C:\\',
                            'label' => 'Windows',
                            'filesystem' => 'NTFS',
                            'serial' => fake()->bothify('????-????'),
                        ],
                    ],
                ],
                'restore_point' => [
                    'sequence' => fake()->numberBetween(1, 50),
                    'created' => true,
                    'message' => null,
                    'size_bytes' => fake()->numberBetween(100000, 900000),
                ],
            ],
        ];
    }
}
