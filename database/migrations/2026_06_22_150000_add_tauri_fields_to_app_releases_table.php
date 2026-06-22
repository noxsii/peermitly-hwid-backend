<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_releases', static function (Blueprint $table): void {
            $table->string('platform')->default('windows-x86_64')->after('version');
            $table->text('signature')->nullable()->after('notes');
            $table->timestamp('published_at')->nullable()->after('file_size');
            $table->boolean('is_active')->default(true)->after('published_at');

            $table->index(['platform', 'is_active', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::table('app_releases', static function (Blueprint $table): void {
            $table->dropIndex(['platform', 'is_active', 'published_at']);
            $table->dropColumn(['platform', 'signature', 'published_at', 'is_active']);
        });
    }
};
