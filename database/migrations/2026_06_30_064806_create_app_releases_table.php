<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_releases', static function (Blueprint $table): void {
            $table->id();
            $table->string('version')->unique();
            $table->text('notes')->nullable();
            $table->json('platforms');
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_releases');
    }
};
