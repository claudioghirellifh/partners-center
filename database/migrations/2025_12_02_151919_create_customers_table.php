<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('cpf_cnpj', 20);
            $table->text('notes')->nullable();
            $table->string('zip_code', 12);
            $table->string('number', 20);
            $table->string('street');
            $table->string('city');
            $table->string('state', 5);
            $table->string('district')->nullable();
            $table->string('complement')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
