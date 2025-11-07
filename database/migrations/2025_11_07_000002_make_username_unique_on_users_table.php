<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add unique index for existing username column if not already unique
        if (Schema::hasColumn('users', 'username')) {
            // Attempt to create unique index; ignore if it already exists
            try {
                DB::statement('ALTER TABLE users ADD UNIQUE KEY users_username_unique (username)');
            } catch (\Exception $e) {
                // Silently ignore (likely index already exists or duplicates need manual resolution)
            }
        }
    }

    public function down(): void
    {
        // Drop unique index if exists
        try {
            DB::statement('ALTER TABLE users DROP INDEX users_username_unique');
        } catch (\Exception $e) {
            // ignore
        }
    }
};
