<?php

declare(strict_types=1);

namespace App\Http\Controllers\LicenseKeys;

use App\Enums\LicenseKeyGeneratorType;
use App\Http\Requests\LicenseKeys\StoreLicenseKeyTypeRequest;
use App\Http\Requests\LicenseKeys\UpdateLicenseKeyTypeRequest;
use App\Http\Resources\LicenseKeys\LicenseKeyTypeResource;
use App\Models\LicenseKeyType;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class LicenseKeyTypeController
{
    public function index(): Response
    {
        $teamId = (int) auth()->user()?->current_team_id;

        return Inertia::render('license-keys/types/Index', [
            'types' => Inertia::defer(static fn () => LicenseKeyTypeResource::collection(
                LicenseKeyType::query()
                    ->where('team_id', $teamId)
                    ->withCount('licenseKeys')
                    ->orderBy('name')
                    ->paginate(25)
                    ->withQueryString(),
            )),
        ]);
    }

    public function store(StoreLicenseKeyTypeRequest $request): RedirectResponse
    {
        $teamId = (int) auth()->user()?->current_team_id;

        LicenseKeyType::query()->create([
            'team_id' => $teamId,
            'name' => $request->string('name'),
            'description' => $request->filled('description') ? $request->string('description') : null,
            'generator_type' => LicenseKeyGeneratorType::from($request->string('generator_type')->toString())->value,
            'configuration' => $request->array('configuration'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'License key type created.');
    }

    public function update(UpdateLicenseKeyTypeRequest $request, LicenseKeyType $licenseKeyType): RedirectResponse
    {
        abort_unless($licenseKeyType->team_id === (int) auth()->user()?->current_team_id, 404);

        $licenseKeyType->forceFill([
            'name' => $request->string('name'),
            'description' => $request->filled('description') ? $request->string('description') : null,
            'generator_type' => LicenseKeyGeneratorType::from($request->string('generator_type')->toString())->value,
            'configuration' => $request->array('configuration'),
            'is_active' => $request->boolean('is_active'),
        ])->save();

        return back()->with('success', 'License key type updated.');
    }

    public function destroy(LicenseKeyType $licenseKeyType): RedirectResponse
    {
        abort_unless($licenseKeyType->team_id === (int) auth()->user()?->current_team_id, 404);

        $licenseKeyType->delete();

        return back()->with('success', 'License key type deleted.');
    }
}
