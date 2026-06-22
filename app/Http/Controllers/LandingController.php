<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

final class LandingController
{
    public function show(): Response
    {
        return Inertia::render('landing/Index', [
            'siteName' => 'Peermitly',
            'canonical' => url('/'),
            'ogImage' => url('/og-image.png'),
        ]);
    }
}
