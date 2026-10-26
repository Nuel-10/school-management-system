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
        Schema::create('class_levels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_section_id')
                ->constrained('school_sections')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['school_section_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_levels');
    }
};