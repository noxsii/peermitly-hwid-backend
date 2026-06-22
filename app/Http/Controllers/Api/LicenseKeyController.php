<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Customers\ResolveCustomerAction;
use App\Actions\LicenseKeys\CreateLicenseKeyAction;
use App\Actions\LicenseKeys\ExtendLicenseKeyAction;
use App\Actions\LicenseKeys\RestoreLicenseKeyAction;
use App\Actions\LicenseKeys\RevokeLicenseKeyAction;
use App\Enums\LicenseValidityUnit;
use App\Http\Requests\LicenseKeys\ExtendLicenseKeyRequest;
use App\Http\Requests\LicenseKeys\RevokeLicenseKeyRequest;
use App\Http\Requests\LicenseKeys\StoreLicenseKeyRequest;
use App\Http\Resources\LicenseKeys\LicenseKeyResource;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

final class LicenseKeyController
{
    /**
     * List license keys.
     *
     * Returns a paginated, team-scoped collection of license keys. Each
     * key embeds its product, type, and (if assigned) customer.
     *
     * Requires token ability: `license-keys:read`.
     */
    public function index(): AnonymousResourceCollection
    {
        $teamId = (int) auth()->user()?->current_team_id;

        return LicenseKeyResource::collection(
            LicenseKey::query()
                ->where('team_id', $teamId)
                ->with(['type', 'product', 'customer'])
                ->latest()
                ->paginate(25)
                ->withQueryString(),
        );
    }

    /**
     * Show a single license key.
     *
     * Returns the full details of one license key, including its
     * activation history. Cross-team access returns `404`.
     *
     * Requires token ability: `license-keys:read`.
     */
    public function show(LicenseKey $licenseKey): JsonResource
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);

        return LicenseKeyResource::make($licenseKey->load(['type', 'product', 'customer', 'activations']));
    }

    /**
     * Create a new license key.
     *
     * Generates a license key for the given product and key type. The key
     * is created in status `pending` — its validity starts on the first
     * successful `POST /api/license-keys/check`.
     *
     * If `customer_uuid` is provided, the key is attached to that
     * customer. Alternatively, pass `customer_email` to attach by email
     * (creates the customer record if it does not exist yet).
     *
     * Requires token ability: `license-keys:manage`.
     */
    public function store(
        StoreLicenseKeyRequest $request,
        CreateLicenseKeyAction $create,
        ResolveCustomerAction $resolveCustomer,
    ): JsonResource {
        $teamId = (int) auth()->user()?->current_team_id;

        $type = LicenseKeyType::query()->where('team_id', $teamId)->where('uuid', $request->string('license_key_type_uuid'))->firstOrFail();
        $product = Product::query()->where('team_id', $teamId)->where('uuid', $request->string('product_uuid'))->firstOrFail();

        $customer = $resolveCustomer->handle(
            $teamId,
            $request->filled('customer_uuid') ? $request->string('customer_uuid')->toString() : null,
            $request->filled('customer_email') ? $request->string('customer_email')->toString() : null,
        );

        $user = $request->user();
        abort_unless($user instanceof User, 401);

        $licenseKey = $create->handle(
            $type,
            $product,
            $customer,
            $request->integer('validity_amount'),
            LicenseValidityUnit::from($request->string('validity_unit')->toString()),
            $request->filled('max_activations') ? $request->integer('max_activations') : null,
            $request->boolean('requires_hwid_check'),
            $request->array('metadata') ?: null,
            $user,
        );

        return LicenseKeyResource::make($licenseKey->load(['type', 'product', 'customer']));
    }

    /**
     * Delete a license key.
     *
     * Permanently removes the key. Prefer `revoke` when you want to
     * keep a record of disabled keys.
     *
     * Requires token ability: `license-keys:manage`.
     */
    public function destroy(LicenseKey $licenseKey): JsonResource
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);

        $licenseKey->delete();

        return LicenseKeyResource::make($licenseKey);
    }

    /**
     * Revoke a license key.
     *
     * Marks the key as `revoked` and records the optional `reason`.
     * The key stops passing checks immediately but is preserved for
     * audit purposes. Use `restore` to bring it back.
     *
     * Requires token ability: `license-keys:manage`.
     */
    public function revoke(RevokeLicenseKeyRequest $request, LicenseKey $licenseKey, RevokeLicenseKeyAction $revoke): JsonResource
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);

        $revoke->handle($licenseKey, $request->string('reason')->toString());

        return LicenseKeyResource::make($licenseKey->fresh());
    }

    /**
     * Restore a revoked license key.
     *
     * Lifts a previous revocation. If the original `expires_at` is still
     * in the future, the key goes back to `active`; otherwise it lands
     * in `expired`.
     *
     * Requires token ability: `license-keys:manage`.
     */
    public function restore(LicenseKey $licenseKey, RestoreLicenseKeyAction $restore): JsonResource
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);

        $restore->handle($licenseKey);

        return LicenseKeyResource::make($licenseKey->fresh());
    }

    /**
     * Extend the validity of a license key.
     *
     * Pushes `expires_at` forward by `amount` units of `unit`
     * (`hours` | `days` | `weeks` | `months` | `years`). Use this to
     * renew subscriptions without issuing a new key.
     *
     * Requires token ability: `license-keys:manage`.
     */
    public function extend(ExtendLicenseKeyRequest $request, LicenseKey $licenseKey, ExtendLicenseKeyAction $extend): JsonResource
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);

        $extend->handle(
            $licenseKey,
            $request->integer('amount'),
            LicenseValidityUnit::from($request->string('unit')->toString()),
        );

        return LicenseKeyResource::make($licenseKey->fresh());
    }
}
