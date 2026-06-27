<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('backups');
    }

    public function down(): void
    {
        // The backups feature was removed; nothing to recreate.
    }
};
