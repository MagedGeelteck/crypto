<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('support_tickets') && !Schema::hasColumn('support_tickets','username')) {
            Schema::table('support_tickets', function (Blueprint $table) {
                $table->string('username',40)->nullable()->after('user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('support_tickets') && Schema::hasColumn('support_tickets','username')) {
            Schema::table('support_tickets', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }
    }
};
