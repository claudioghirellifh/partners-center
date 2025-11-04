<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            // Drop existing foreign key and recreate with cascade on delete
            $table->dropForeign(['company_id']);
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['company_id']);
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->nullOnDelete();
        });
    }
};

