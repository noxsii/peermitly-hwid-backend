<?php

declare(strict_types=1);

namespace App\Actions\News;

use App\Models\News;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

final class GenerateNewsSlugAction
{
    public function handle(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = 'news';
        }

        $slug = $base;
        $suffix = 2;

        while ($this->exists($slug, $ignoreId)) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function exists(string $slug, ?int $ignoreId): bool
    {
        return News::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn (Builder $query): Builder => $query->whereKeyNot($ignoreId))
            ->exists();
    }
}
