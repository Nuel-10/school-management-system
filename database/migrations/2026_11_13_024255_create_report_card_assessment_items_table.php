<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_card_assessment_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_section_id')
                ->nullable()
                ->constrained('school_sections')
                ->nullOnDelete();

            $table->string('category'); // behaviour, psychomotor, affective
            $table->string('name');
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_card_assessment_items');
    }
};