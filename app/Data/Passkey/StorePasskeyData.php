<?php

declare(strict_types=1);

namespace App\Data\Passkey;

use App\Models\User;

final readonly class StorePasskeyData
{
    /**
     * @param  array<string, mixed>  $credential
     */
    public function __construct(
        public User $user,
        public string $name,
        public array $credential,
    ) {}
}
