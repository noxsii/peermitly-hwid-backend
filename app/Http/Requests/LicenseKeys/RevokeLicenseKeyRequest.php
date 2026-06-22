<?php

declare(strict_types=1);

namespace App\Http\Requests\LicenseKeys;

use Illuminate\Foundation\Http\FormRequest;

final class RevokeLicenseKeyRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
