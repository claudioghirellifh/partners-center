<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            if (Schema::hasColumn('projects', 'billing_cycle')) {
                $table->dropColumn('billing_cycle');
            }

            if (Schema::hasColumn('projects', 'starts_on')) {
                $table->dropColumn('starts_on');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            if (!Schema::hasColumn('projects', 'billing_cycle')) {
                $table->string('billing_cycle')->default('monthly');
            }

            if (!Schema::hasColumn('projects', 'starts_on')) {
                $table->date('starts_on')->nullable();
            }
        });
    }
};
