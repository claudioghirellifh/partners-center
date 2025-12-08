<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            if (!Schema::hasColumn('projects', 'store_domain')) {
                $table->string('store_domain')->nullable()->after('client_email');
            }
            if (!Schema::hasColumn('projects', 'store_name')) {
                $table->string('store_name')->nullable()->after('store_domain');
            }
            if (!Schema::hasColumn('projects', 'store_admin_name')) {
                $table->string('store_admin_name')->nullable()->after('store_name');
            }
            if (!Schema::hasColumn('projects', 'store_admin_email')) {
                $table->string('store_admin_email')->nullable()->after('store_admin_name');
            }
            if (!Schema::hasColumn('projects', 'store_admin_password')) {
                $table->string('store_admin_password')->nullable()->after('store_admin_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            if (Schema::hasColumn('projects', 'store_admin_password')) {
                $table->dropColumn('store_admin_password');
            }
            if (Schema::hasColumn('projects', 'store_admin_email')) {
                $table->dropColumn('store_admin_email');
            }
            if (Schema::hasColumn('projects', 'store_admin_name')) {
                $table->dropColumn('store_admin_name');
            }
            if (Schema::hasColumn('projects', 'store_name')) {
                $table->dropColumn('store_name');
            }
            if (Schema::hasColumn('projects', 'store_domain')) {
                $table->dropColumn('store_domain');
            }
        });
    }
};
