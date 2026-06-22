<?php

declare(strict_types=1);

namespace App\Http\Requests\Passkey;

use Illuminate\Foundation\Http\FormRequest;

final class StorePasskeyRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'credential' => ['required', 'array'],
        ];
    }
}
