<?php

declare(strict_types=1);

namespace App\Enums;

enum LicenseCheckStatus: string
{
    case VALID = 'valid';
    case ACTIVATED = 'activated';
    case EXPIRED = 'expired';
    case REVOKED = 'revoked';
    case BLOCKED = 'blocked';
    case INVALID = 'invalid';
    case NOT_FOUND = 'not_found';
    case PRODUCT_MISMATCH = 'product_mismatch';
    case ACTIVATION_LIMIT_REACHED = 'activation_limit_reached';
    case HWID_REQUIRED = 'hwid_required';
    case HWID_MISMATCH = 'hwid_mismatch';

    public function isValid(): bool
    {
        return in_array($this, [self::VALID, self::ACTIVATED], strict: true);
    }
}
