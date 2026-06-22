<?php

declare(strict_types=1);

namespace App\Http\Controllers\LicenseKeys;

use App\Http\Requests\LicenseKeys\StoreProductRequest;
use App\Http\Requests\LicenseKeys\UpdateProductRequest;
use App\Http\Resources\LicenseKeys\ProductResource;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class ProductController
{
    public function index(): Response
    {
        $teamId = (int) auth()->user()?->current_team_id;

        return Inertia::render('license-keys/products/Index', [
            'products' => Inertia::defer(static fn () => ProductResource::collection(
                Product::query()
                    ->where('team_id', $teamId)
                    ->withCount('licenseKeys')
                    ->orderBy('name')
                    ->paginate(25)
                    ->withQueryString(),
            )),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $teamId = (int) auth()->user()?->current_team_id;

        $request->validate([
            'slug' => [Rule::unique('products', 'slug')->where('team_id', $teamId)],
        ]);

        Product::query()->create([
            'team_id' => $teamId,
            'name' => $request->string('name'),
            'slug' => $request->string('slug'),
            'description' => $request->filled('description') ? $request->string('description') : null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Product created.');
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        abort_unless($product->team_id === (int) auth()->user()?->current_team_id, 404);

        $request->validate([
            'slug' => [Rule::unique('products', 'slug')->where('team_id', $product->team_id)->ignore($product->id)],
        ]);

        $product->forceFill([
            'name' => $request->string('name'),
            'slug' => $request->string('slug'),
            'description' => $request->filled('description') ? $request->string('description') : null,
            'is_active' => $request->boolean('is_active'),
        ])->save();

        return back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        abort_unless($product->team_id === (int) auth()->user()?->current_team_id, 404);

        $product->delete();

        return back()->with('success', 'Product deleted.');
    }
}
