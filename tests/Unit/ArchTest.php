<?php

declare(strict_types=1);

use App\Data\LicenseKeys\LicenseKeyConfiguration;
use App\Http\Middleware\FilamentAuthenticate;
use App\Providers\HorizonServiceProvider;

arch()->preset()->php();
arch()->preset()->strict()->ignoring([LicenseKeyConfiguration::class, FilamentAuthenticate::class, HorizonServiceProvider::class, 'App\Filament']);
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
    ->toBeClasses()
    ->ignoring(LicenseKeyConfiguration::class);

arch('no invokable controllers in licensekeys namespace')
    ->expect('App\Http\Controllers\LicenseKeys')
    ->not->toHaveMethod('__invoke');

arch('no invokable controllers in api namespace')
    ->expect('App\Http\Controllers\Api')
    ->not->toHaveMethod('__invoke');

arch('no invokable controllers in team namespace')
    ->expect('App\Http\Controllers\Team')
    ->not->toHaveMethod('__invoke');
