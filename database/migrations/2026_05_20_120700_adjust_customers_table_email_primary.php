<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', static function (Blueprint $table): void {
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable(false)->change();
            $table->dropIndex(['team_id', 'email']);
            $table->unique(['team_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::table('customers', static function (Blueprint $table): void {
            $table->dropUnique(['team_id', 'email']);
            $table->index(['team_id', 'email']);
            $table->string('email')->nullable()->change();
            $table->string('name')->nullable(false)->change();
        });
    }
};
