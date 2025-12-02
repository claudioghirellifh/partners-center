<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->string('iugu_customer_id')->nullable()->after('company_id');
        });

        Schema::table('projects', function (Blueprint $table): void {
            $table->string('billing_origin')->default('manual')->after('billing_cycle');
            $table->string('iugu_subscription_id')->nullable()->after('billing_origin');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn('iugu_customer_id');
        });

        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn(['billing_origin', 'iugu_subscription_id']);
        });
    }
};
