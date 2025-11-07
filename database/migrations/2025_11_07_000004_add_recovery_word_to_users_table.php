<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'recovery_word')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('recovery_word')->nullable()->after('username');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'recovery_word')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('recovery_word');
            });
        }
    }
};
