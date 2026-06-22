<?php

declare(strict_types=1);

namespace App\Http\Requests\LicenseKeys;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateLicenseKeyRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'customer_uuid' => ['nullable', 'uuid', 'exists:customers,uuid'],
            'customer_email' => ['nullable', 'email', 'max:200'],
            'max_activations' => ['nullable', 'integer', 'min:1'],
            'requires_hwid_check' => ['required', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
