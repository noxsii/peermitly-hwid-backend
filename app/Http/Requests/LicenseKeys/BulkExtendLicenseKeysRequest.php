<?php

declare(strict_types=1);

namespace App\Http\Requests\LicenseKeys;

use App\Enums\LicenseValidityUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class BulkExtendLicenseKeysRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $allowedUnits = array_values(array_filter(
            array_map(static fn (LicenseValidityUnit $u): string => $u->value, LicenseValidityUnit::cases()),
            static fn (string $value): bool => $value !== LicenseValidityUnit::LIFETIME->value,
        ));

        return [
            'from_expires_at' => ['required', 'date'],
            'amount' => ['required', 'integer', 'min:1', 'max:10000'],
            'unit' => ['required', 'string', Rule::in($allowedUnits)],
        ];
    }
}
