<?php

declare(strict_types=1);

namespace App\Http\Requests\LicenseKeys;

use App\Enums\LicenseKeyGeneratorType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateLicenseKeyTypeRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'generator_type' => ['required', Rule::enum(LicenseKeyGeneratorType::class)],
            'is_active' => ['required', 'boolean'],
            'configuration' => ['required', 'array'],
        ];
    }
}
