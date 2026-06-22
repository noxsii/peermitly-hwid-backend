<?php

declare(strict_types=1);

namespace App\Http\Controllers\LicenseKeys;

use App\Actions\LicenseKeys\CreateLicenseKeyAction;
use App\Enums\LicenseKeyStatus;
use App\Enums\LicenseValidityUnit;
use App\Http\Requests\LicenseKeys\BulkExtendLicenseKeysRequest;
use App\Http\Requests\LicenseKeys\BulkStoreLicenseKeyRequest;
use App\Http\Requests\LicenseKeys\StoreLicenseKeyRequest;
use App\Http\Requests\LicenseKeys\UpdateLicenseKeyRequest;
use App\Http\Resources\LicenseKeys\CustomerResource;
use App\Http\Resources\LicenseKeys\LicenseKeyResource;
use App\Http\Resources\LicenseKeys\LicenseKeyTypeResource;
use App\Http\Resources\LicenseKeys\ProductResource;
use App\Jobs\BulkCreateLicenseKeysJob;
use App\Jobs\BulkExtendLicenseKeysJob;
use App\Models\Customer;
use App\Models\LicenseKey;
use App\Models\LicenseKeyType;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class LicenseKeyController
{
    public function index(Request $request): Response
    {
        $teamId = (int) auth()->user()?->current_team_id;
        $statusFilter = $request->string('status')->toString();
        $activeStatus = LicenseKeyStatus::tryFrom($statusFilter);

        return Inertia::render('license-keys/Index', [
            'filters' => [
                'status' => $activeStatus?->value,
            ],
            'types' => Inertia::defer(static fn () => LicenseKeyTypeResource::collection(
                LicenseKeyType::query()
                    ->where('team_id', $teamId)
                    ->withCount('licenseKeys')
                    ->whereActive()
                    ->orderBy('name')
                    ->get(),
            )),
            'products' => Inertia::defer(static fn () => ProductResource::collection(
                Product::query()->where('team_id', $teamId)->where('is_active', true)->orderBy('name')->get(),
            )),
            'customers' => Inertia::defer(static fn () => CustomerResource::collection(
                Customer::query()->where('team_id', $teamId)->orderBy('email')->get(),
            )),
            'licenseKeys' => Inertia::defer(static fn () => LicenseKeyResource::collection(
                LicenseKey::query()
                    ->where('team_id', $teamId)
                    ->when($activeStatus, fn ($q, $s) => $q->where('status', $s->value))
                    ->with(['type', 'product', 'customer'])
                    ->latest()
                    ->paginate(25)
                    ->withQueryString(),
            )),
        ]);
    }

    public function store(StoreLicenseKeyRequest $request, CreateLicenseKeyAction $create): RedirectResponse
    {
        $teamId = (int) auth()->user()?->current_team_id;

        $type = LicenseKeyType::query()->where('team_id', $teamId)->where('uuid', $request->string('license_key_type_uuid'))->firstOrFail();
        $product = Product::query()->where('team_id', $teamId)->where('uuid', $request->string('product_uuid'))->firstOrFail();
        $customer = $request->filled('customer_uuid')
            ? Customer::query()->where('team_id', $teamId)->where('uuid', $request->string('customer_uuid'))->first()
            : null;

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

        return to_route('license-keys.show', $licenseKey->uuid);
    }

    public function bulkStore(BulkStoreLicenseKeyRequest $request): RedirectResponse
    {
        $teamId = (int) auth()->user()?->current_team_id;

        $type = LicenseKeyType::query()->where('team_id', $teamId)->where('uuid', $request->string('license_key_type_uuid'))->firstOrFail();
        $product = Product::query()->where('team_id', $teamId)->where('uuid', $request->string('product_uuid'))->firstOrFail();
        $customer = $request->filled('customer_uuid')
            ? Customer::query()->where('team_id', $teamId)->where('uuid', $request->string('customer_uuid'))->first()
            : null;

        $user = $request->user();
        abort_unless($user instanceof User, 401);

        $count = $request->integer('count');

        dispatch(new BulkCreateLicenseKeysJob(typeId: $type->id, productId: $product->id, customerId: $customer?->id, count: $count, validityUnit: $request->string('validity_unit')->toString(), validityAmount: $request->integer('validity_amount'), maxActivations: $request->filled('max_activations') ? $request->integer('max_activations') : null, requiresHwidCheck: $request->boolean('requires_hwid_check'), createdById: $user->id));

        return back()->with(
            'success',
            sprintf('Generating %d license keys in the background — you will be notified when it is done.', $count),
        );
    }

    public function bulkExtend(BulkExtendLicenseKeysRequest $request): RedirectResponse
    {
        $teamId = (int) auth()->user()?->current_team_id;

        $user = $request->user();
        abort_unless($user instanceof User, 401);

        $from = $request->date('from_expires_at');
        abort_if($from === null, 422, 'Invalid date.');

        $amount = $request->integer('amount');
        $unit = $request->string('unit')->toString();

        dispatch(new BulkExtendLicenseKeysJob(teamId: $teamId, fromExpiresAtIso: $from->toIso8601String(), amount: $amount, unit: $unit, createdById: $user->id));

        return back()->with(
            'success',
            sprintf(
                'Extending license keys expiring after %s by %d %s in the background — you will be notified when it is done.',
                $from->format('Y-m-d H:i'),
                $amount,
                $unit,
            ),
        );
    }

    public function show(LicenseKey $licenseKey): Response
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);
        $teamId = (int) auth()->user()?->current_team_id;

        return Inertia::render('license-keys/Show', [
            'licenseKey' => LicenseKeyResource::make(
                $licenseKey->load(['type', 'product', 'customer', 'activations']),
            ),
            'customers' => Inertia::defer(static fn () => CustomerResource::collection(
                Customer::query()->where('team_id', $teamId)->orderBy('email')->get(),
            )),
        ]);
    }

    public function update(UpdateLicenseKeyRequest $request, LicenseKey $licenseKey): RedirectResponse
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);

        $teamId = (int) auth()->user()?->current_team_id;
        $customer = $request->filled('customer_uuid')
            ? Customer::query()->where('team_id', $teamId)->where('uuid', $request->string('customer_uuid'))->first()
            : null;

        $licenseKey->forceFill([
            'customer_id' => $customer?->id,
            'max_activations' => $request->filled('max_activations') ? $request->integer('max_activations') : null,
            'requires_hwid_check' => $request->boolean('requires_hwid_check'),
            'metadata' => $request->array('metadata') ?: null,
        ])->save();

        return back();
    }

    public function destroy(LicenseKey $licenseKey): RedirectResponse
    {
        abort_unless($licenseKey->team_id === (int) auth()->user()?->current_team_id, 404);

        $licenseKey->delete();

        return to_route('license-keys.index')->with('success', 'License key deleted.');
    }
}
