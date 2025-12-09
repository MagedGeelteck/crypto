<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to improve query performance
        
        // Deposits table indexes
        if (Schema::hasTable('deposits')) {
            Schema::table('deposits', function (Blueprint $table) {
                if (!$this->indexExists('deposits', 'deposits_user_id_status_index')) {
                    $table->index(['user_id', 'status'], 'deposits_user_id_status_index');
                }
                if (!$this->indexExists('deposits', 'deposits_code_index')) {
                    $table->index('code');
                }
                if (!$this->indexExists('deposits', 'deposits_trx_index')) {
                    $table->index('trx');
                }
                if (!$this->indexExists('deposits', 'deposits_created_at_index')) {
                    $table->index('created_at');
                }
            });
        }
        
        // Sells table indexes
        if (Schema::hasTable('sells')) {
            Schema::table('sells', function (Blueprint $table) {
                if (!$this->indexExists('sells', 'sells_user_id_status_index')) {
                    $table->index(['user_id', 'status'], 'sells_user_id_status_index');
                }
                if (!$this->indexExists('sells', 'sells_code_index')) {
                    $table->index('code');
                }
                if (!$this->indexExists('sells', 'sells_product_id_index')) {
                    $table->index('product_id');
                }
            });
        }
        
        // Products table indexes
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!$this->indexExists('products', 'products_status_index')) {
                    $table->index('status');
                }
                if (!$this->indexExists('products', 'products_category_id_index')) {
                    $table->index('category_id');
                }
                if (!$this->indexExists('products', 'products_sub_category_id_index')) {
                    $table->index('sub_category_id');
                }
            });
        }
        
        // Orders table indexes
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!$this->indexExists('orders', 'orders_order_number_index')) {
                    $table->index('order_number');
                }
                if (!$this->indexExists('orders', 'orders_product_id_index')) {
                    $table->index('product_id');
                }
            });
        }
        
        // Support tickets indexes
        if (Schema::hasTable('support_tickets')) {
            Schema::table('support_tickets', function (Blueprint $table) {
                if (!$this->indexExists('support_tickets', 'support_tickets_user_id_status_index')) {
                    $table->index(['user_id', 'status'], 'support_tickets_user_id_status_index');
                }
                if (!$this->indexExists('support_tickets', 'support_tickets_ticket_index')) {
                    $table->index('ticket');
                }
            });
        }
        
        // Users table indexes
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!$this->indexExists('users', 'users_email_index')) {
                    $table->index('email');
                }
                if (!$this->indexExists('users', 'users_username_index')) {
                    $table->index('username');
                }
                if (!$this->indexExists('users', 'users_status_index')) {
                    $table->index('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes
        if (Schema::hasTable('deposits')) {
            Schema::table('deposits', function (Blueprint $table) {
                $table->dropIndex('deposits_user_id_status_index');
                $table->dropIndex('deposits_code_index');
                $table->dropIndex('deposits_trx_index');
                $table->dropIndex('deposits_created_at_index');
            });
        }
        
        if (Schema::hasTable('sells')) {
            Schema::table('sells', function (Blueprint $table) {
                $table->dropIndex('sells_user_id_status_index');
                $table->dropIndex('sells_code_index');
                $table->dropIndex('sells_product_id_index');
            });
        }
        
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_status_index');
                $table->dropIndex('products_category_id_index');
                $table->dropIndex('products_sub_category_id_index');
            });
        }
        
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('orders_order_number_index');
                $table->dropIndex('orders_product_id_index');
            });
        }
        
        if (Schema::hasTable('support_tickets')) {
            Schema::table('support_tickets', function (Blueprint $table) {
                $table->dropIndex('support_tickets_user_id_status_index');
                $table->dropIndex('support_tickets_ticket_index');
            });
        }
        
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_email_index');
                $table->dropIndex('users_username_index');
                $table->dropIndex('users_status_index');
            });
        }
    }
    
    /**
     * Check if an index exists
     */
    protected function indexExists($table, $index)
    {
        $indexes = DB::select("SHOW INDEX FROM $table WHERE Key_name = ?", [$index]);
        return !empty($indexes);
    }
};
