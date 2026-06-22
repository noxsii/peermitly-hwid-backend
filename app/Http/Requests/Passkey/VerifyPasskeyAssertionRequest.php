<?php

declare(strict_types=1);

namespace App\Http\Requests\Passkey;

use Illuminate\Foundation\Http\FormRequest;

final class VerifyPasskeyAssertionRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'assertion' => ['required', 'array'],
        ];
    }
}
