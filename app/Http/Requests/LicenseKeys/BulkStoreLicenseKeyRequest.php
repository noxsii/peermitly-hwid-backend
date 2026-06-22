<?php

declare(strict_types=1);

namespace App\Http\Requests\LicenseKeys;

use App\Enums\LicenseValidityUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class BulkStoreLicenseKeyRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'license_key_type_uuid' => ['required', 'uuid', 'exists:license_key_types,uuid'],
            'product_uuid' => ['required', 'uuid', 'exists:products,uuid'],
            'customer_uuid' => ['nullable', 'uuid', 'exists:customers,uuid'],
            'customer_email' => ['nullable', 'email', 'max:200'],
            'count' => ['required', 'integer', 'min:1', 'max:1000'],
            'validity_unit' => ['required', Rule::enum(LicenseValidityUnit::class)],
            'validity_amount' => ['required_unless:validity_unit,lifetime', 'nullable', 'integer', 'min:1'],
            'max_activations' => ['nullable', 'integer', 'min:1'],
            'requires_hwid_check' => ['required', 'boolean'],
        ];
    }
}
