<?php

declare(strict_types=1);

use App\Data\Team\UpdateTeamData;

test('exposes name as a public readonly string property', function (): void {
    $data = new UpdateTeamData(name: 'Acme GmbH');

    expect($data->name)->toBe('Acme GmbH');
});

test('is final and readonly', function (): void {
    $reflection = new ReflectionClass(UpdateTeamData::class);

    expect($reflection->isFinal())->toBeTrue();
    expect($reflection->isReadOnly())->toBeTrue();
});

test('name property cannot be mutated after construction', function (): void {
    $data = new UpdateTeamData(name: 'Original');

    expect(fn (): string => $data->name = 'Mutated')->toThrow(Error::class);
});
