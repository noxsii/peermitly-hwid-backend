<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

final class StoreBackupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'max:255'],
            'created_at' => ['nullable', 'integer'],
            'label' => ['nullable', 'string', 'max:255'],
            'snapshot' => ['nullable', 'array'],
            'snapshot.machine_guid' => ['nullable', 'string', 'max:255'],
            'snapshot.disks' => ['nullable', 'array'],
            'restore_point' => ['nullable', 'array'],
            'file_size_bytes' => ['nullable', 'integer'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->all();

        return $data;
    }
}
