<?php

declare(strict_types=1);

namespace App\Actions\Docs;

use Illuminate\Support\Collection;

final readonly class GetDocSlugsAction
{
    /**
     * Flatten the documentation navigation into the ordered list of valid
     * slugs used for route validation and prev/next ordering.
     *
     * @param  array<int, array{title: string, items: array<int, array{slug: string, title: string, pro?: bool}>}>  $sections
     * @return Collection<int, string>
     */
    public function handle(array $sections): Collection
    {
        return collect($sections)
            ->flatMap(static fn (array $section): array => $section['items'])
            ->map(static fn (array $item): string => $item['slug'])
            ->values();
    }
}
