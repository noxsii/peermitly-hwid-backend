<?php

declare(strict_types=1);

namespace App\Http\Controllers\LicenseKeys;

use App\Http\Requests\LicenseKeys\StoreCustomerRequest;
use App\Http\Requests\LicenseKeys\UpdateCustomerRequest;
use App\Http\Resources\LicenseKeys\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class CustomerController
{
    public function index(): Response
    {
        $teamId = (int) auth()->user()?->current_team_id;

        return Inertia::render('license-keys/customers/Index', [
            'customers' => Inertia::defer(static fn () => CustomerResource::collection(
                Customer::query()
                    ->where('team_id', $teamId)
                    ->withCount('licenseKeys')
                    ->orderBy('email')
                    ->paginate(25)
                    ->withQueryString(),
            )),
        ]);
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $teamId = (int) auth()->user()?->current_team_id;

        $request->validate([
            'email' => [Rule::unique('customers', 'email')->where('team_id', $teamId)],
        ]);

        Customer::query()->create([
            'team_id' => $teamId,
            'email' => $request->string('email'),
            'name' => $request->filled('name') ? $request->string('name') : null,
            'company' => $request->filled('company') ? $request->string('company') : null,
            'metadata' => $request->filled('metadata') ? $request->array('metadata') : null,
        ]);

        return back()->with('success', 'Customer created.');
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        abort_unless($customer->team_id === (int) auth()->user()?->current_team_id, 404);

        $request->validate([
            'email' => [Rule::unique('customers', 'email')->where('team_id', $customer->team_id)->ignore($customer->id)],
        ]);

        $customer->forceFill([
            'email' => $request->string('email'),
            'name' => $request->filled('name') ? $request->string('name') : null,
            'company' => $request->filled('company') ? $request->string('company') : null,
            'metadata' => $request->filled('metadata') ? $request->array('metadata') : null,
        ])->save();

        return back()->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        abort_unless($customer->team_id === (int) auth()->user()?->current_team_id, 404);

        $customer->delete();

        return back()->with('success', 'Customer deleted.');
    }
}
