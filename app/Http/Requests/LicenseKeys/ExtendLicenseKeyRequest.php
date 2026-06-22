<?php

declare(strict_types=1);

namespace App\Http\Requests\LicenseKeys;

use App\Enums\LicenseValidityUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ExtendLicenseKeyRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1'],
            'unit' => ['required', Rule::enum(LicenseValidityUnit::class)],
        ];
    }
}
