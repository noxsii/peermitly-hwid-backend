<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\LicenseKeyGeneratorType;
use App\Http\Requests\LicenseKeys\StoreLicenseKeyTypeRequest;
use App\Http\Requests\LicenseKeys\UpdateLicenseKeyTypeRequest;
use App\Http\Resources\LicenseKeys\LicenseKeyTypeResource;
use App\Models\LicenseKeyType;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

final class LicenseKeyTypeController
{
    /**
     * List license key types.
     *
     * Returns a paginated, team-scoped list of key types. Each type
     * defines how keys are generated (UUID, random characters, or
     * pattern-based) and includes a `license_keys_count` showing how
     * many keys currently use this type.
     *
     * Requires token ability: `license-key-types:manage`.
     */
    public function index(): AnonymousResourceCollection
    {
        $teamId = (int) auth()->user()?->current_team_id;

        return LicenseKeyTypeResource::collection(
            LicenseKeyType::query()
                ->where('team_id', $teamId)
                ->withCount('licenseKeys')
                ->orderBy('name')
                ->paginate(25)
                ->withQueryString(),
        );
    }

    /**
     * Show a single license key type.
     *
     * Returns the type's full configuration. Cross-team access returns `404`.
     *
     * Requires token ability: `license-key-types:manage`.
     */
    public function show(LicenseKeyType $licenseKeyType): JsonResource
    {
        abort_unless($licenseKeyType->team_id === (int) auth()->user()?->current_team_id, 404);

        return LicenseKeyTypeResource::make($licenseKeyType);
    }

    /**
     * Create a license key type.
     *
     * Defines a new key format. The `configuration` shape depends on the
     * `generator_type`:
     *
     * - `uuid` — `uuid_version`, `with_hyphens`, `uppercase`, optional
     *   `prefix` / `suffix`.
     * - `random` — `length`, `group_length`, `separator`, character class
     *   toggles, optional `custom_charset`.
     * - `pattern` — `pattern` template (e.g. `PRMT-####-AAAA-####`).
     *
     * Requires token ability: `license-key-types:manage`.
     */
    public function store(StoreLicenseKeyTypeRequest $request): JsonResource
    {
        $teamId = (int) auth()->user()?->current_team_id;

        $type = LicenseKeyType::query()->create([
            'team_id' => $teamId,
            'name' => $request->string('name'),
            'description' => $request->filled('description') ? $request->string('description') : null,
            'generator_type' => LicenseKeyGeneratorType::from($request->string('generator_type')->toString())->value,
            'configuration' => $request->array('configuration'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return LicenseKeyTypeResource::make($type);
    }

    /**
     * Update a license key type.
     *
     * Replaces name, description, generator type, configuration, and
     * active flag. Already-issued keys keep their previous values —
     * changes only affect keys created after the update.
     *
     * Requires token ability: `license-key-types:manage`.
     */
    public function update(UpdateLicenseKeyTypeRequest $request, LicenseKeyType $licenseKeyType): JsonResource
    {
        abort_unless($licenseKeyType->team_id === (int) auth()->user()?->current_team_id, 404);

        $licenseKeyType->forceFill([
            'name' => $request->string('name'),
            'description' => $request->filled('description') ? $request->string('description') : null,
            'generator_type' => LicenseKeyGeneratorType::from($request->string('generator_type')->toString())->value,
            'configuration' => $request->array('configuration'),
            'is_active' => $request->boolean('is_active'),
        ])->save();

        return LicenseKeyTypeResource::make($licenseKeyType->fresh());
    }

    /**
     * Delete a license key type.
     *
     * Removes the type definition. License keys that referenced this
     * type keep their already-issued values but become orphaned
     * (their `type` relation becomes `null`).
     *
     * Requires token ability: `license-key-types:manage`.
     */
    public function destroy(LicenseKeyType $licenseKeyType): JsonResource
    {
        abort_unless($licenseKeyType->team_id === (int) auth()->user()?->current_team_id, 404);

        $licenseKeyType->delete();

        return LicenseKeyTypeResource::make($licenseKeyType);
    }
}
