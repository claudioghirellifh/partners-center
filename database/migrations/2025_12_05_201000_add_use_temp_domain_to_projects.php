<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            if (!Schema::hasColumn('projects', 'use_temp_domain')) {
                $table->boolean('use_temp_domain')->default(false)->after('store_domain');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            if (Schema::hasColumn('projects', 'use_temp_domain')) {
                $table->dropColumn('use_temp_domain');
            }
        });
    }
};
