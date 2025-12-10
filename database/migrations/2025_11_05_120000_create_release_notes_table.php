<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('release_notes', function (Blueprint $table): void {
            $table->id();
            $table->string('version', 50)->unique();
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_current')->default(false)->index();
            $table->boolean('is_visible')->default(true)->index();
            $table->string('alert_level', 20)->nullable();
            $table->text('alert_message')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('release_notes');
    }
};
