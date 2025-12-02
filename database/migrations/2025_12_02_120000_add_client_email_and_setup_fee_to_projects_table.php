<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->string('client_email')->nullable()->after('name');
            $table->boolean('charge_setup')->default(false)->after('billing_cycle');
            $table->decimal('setup_fee', 10, 2)->nullable()->after('charge_setup');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn(['client_email', 'charge_setup', 'setup_fee']);
        });
    }
};
