<?php

declare(strict_types=1);

namespace App\Http\Requests\LicenseKeys;

use App\Enums\LicenseKeyGeneratorType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class PreviewLicenseKeyTypeRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'generator_type' => ['required', Rule::enum(LicenseKeyGeneratorType::class)],
            'configuration' => ['required', 'array'],
        ];
    }
}
