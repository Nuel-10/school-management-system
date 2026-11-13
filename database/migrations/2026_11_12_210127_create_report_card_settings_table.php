<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_card_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_section_id')
                ->nullable()
                ->constrained('school_sections')
                ->nullOnDelete();

            $table->string('template_name')->default('Modern Professional');
            $table->string('template_key')->default('modern');

            $table->boolean('show_teacher_comment')->default(true);
            $table->boolean('show_principal_comment')->default(true);
            $table->boolean('show_behaviour')->default(true);
            $table->boolean('show_psychomotor')->default(true);
            $table->boolean('show_affective')->default(true);
            $table->boolean('show_attendance')->default(true);
            $table->boolean('show_class_position')->default(true);
            $table->boolean('show_next_term_date')->default(true);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_card_settings');
    }
};