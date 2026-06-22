<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

final class CheckLicenseKeyRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'min:4', 'max:200'],
            'product' => ['required', 'string', 'max:200'],
            'hwid' => ['nullable', 'string', 'max:200'],
        ];
    }
}
