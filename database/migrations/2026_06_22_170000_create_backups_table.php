<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backups', static function (Blueprint $table): void {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('client_backup_id');
            $table->string('label')->nullable();
            $table->string('machine_guid')->nullable()->index();
            $table->timestamp('client_created_at')->nullable();
            $table->json('data');

            $table->timestamps();

            $table->unique(['user_id', 'client_backup_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
