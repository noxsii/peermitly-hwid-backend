<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('license_key_activations', static function (Blueprint $table): void {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('license_key_id')->constrained('license_keys')->cascadeOnDelete();
            $table->string('machine_id');
            $table->string('hostname')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('activated_at');
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['license_key_id', 'machine_id']);
            $table->index(['team_id', 'license_key_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license_key_activations');
    }
};
