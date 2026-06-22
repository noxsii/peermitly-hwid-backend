<?php

declare(strict_types=1);

use App\Http\Middleware\FilamentAuthenticate;
use App\Providers\HorizonServiceProvider;

arch()->preset()->php();
arch()->preset()->strict()->ignoring([FilamentAuthenticate::class, HorizonServiceProvider::class, 'App\Filament']);
arch()->preset()->security();

arch('controllers')
    ->expect('App\Http\Controllers')
    ->not->toBeUsed();

arch('actions are final classes with handle method')
    ->expect('App\Actions')
    ->toBeClasses()
    ->toBeFinal();

arch('models live in App\Models')
    ->expect('App\Models')
    ->toBeClasses();

arch('enums in App\Enums namespace')
    ->expect('App\Enums')
    ->toBeEnums();

arch('dtos are final readonly')
    ->expect('App\Data')
    ->toBeClasses();

arch('no invokable controllers in api namespace')
    ->expect('App\Http\Controllers\Api')
    ->not->toHaveMethod('__invoke');
