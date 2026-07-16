<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\News;
use Illuminate\Support\Facades\Storage;

final readonly class NewsObserver
{
    public function deleting(News $news): void
    {
        if (filled($news->image_path)) {
            Storage::disk('public')->delete($news->image_path);
        }
    }
}
