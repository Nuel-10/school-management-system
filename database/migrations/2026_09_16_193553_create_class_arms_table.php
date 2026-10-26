<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_arms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('class_level_id')
                ->constrained('class_levels')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['class_level_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_arms');
    }
};