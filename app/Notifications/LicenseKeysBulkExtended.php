<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\LicenseValidityUnit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Attributes\Queue;

#[Queue('notifications')]
final class LicenseKeysBulkExtended extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $count,
        public readonly int $amount,
        public readonly LicenseValidityUnit $unit,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(): array
    {
        return [
            'title' => 'License keys extended',
            'message' => sprintf(
                '%d license keys were extended by %d %s in the background.',
                $this->count,
                $this->amount,
                $this->unit->value,
            ),
            'count' => $this->count,
            'amount' => $this->amount,
            'unit' => $this->unit->value,
            'url' => '/license-keys',
        ];
    }
}
