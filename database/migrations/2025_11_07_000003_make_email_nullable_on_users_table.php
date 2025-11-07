<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to modify column to nullable (avoid requiring doctrine/dbal for simple change)
        try {
            DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
        } catch (\Exception $e) {
            // silently ignore if already nullable or error occurs
        }
    }

    public function down(): void
    {
        try {
            DB::statement('UPDATE users SET email = CONCAT("user", id, "@example.local") WHERE email IS NULL');
            DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');
        } catch (\Exception $e) {
            // ignore
        }
    }
};
