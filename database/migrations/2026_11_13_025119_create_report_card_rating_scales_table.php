<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_card_rating_scales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_section_id')
                ->nullable()
                ->constrained('school_sections')
                ->nullOnDelete();

            $table->string('label');
            $table->decimal('score', 5, 2);
            $table->string('description')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['school_section_id', 'score'],
                'report_card_rating_section_score_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_card_rating_scales');
    }
};