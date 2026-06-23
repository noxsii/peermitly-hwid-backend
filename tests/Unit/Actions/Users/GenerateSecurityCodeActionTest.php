<?php

declare(strict_types=1);

use App\Actions\Users\GenerateSecurityCodeAction;

test('it generates a four character code', function (): void {
    $code = resolve(GenerateSecurityCodeAction::class)->handle();

    expect($code)->toHaveLength(4);
});

test('it only uses unambiguous characters', function (): void {
    $action = resolve(GenerateSecurityCodeAction::class);

    foreach (range(1, 50) as $ignored) {
        expect($action->handle())->toMatch('/^[23456789ABCDEFGHJKMNPQRSTUVWXYZ]{4}$/');
    }
});
