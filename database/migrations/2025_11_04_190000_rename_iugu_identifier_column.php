<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table): void {
            if (Schema::hasColumn('plans', 'iugu_identifier') && ! Schema::hasColumn('plans', 'plan_id')) {
                $table->renameColumn('iugu_identifier', 'plan_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table): void {
            if (Schema::hasColumn('plans', 'plan_id') && ! Schema::hasColumn('plans', 'iugu_identifier')) {
                $table->renameColumn('plan_id', 'iugu_identifier');
            }
        });
    }
};
