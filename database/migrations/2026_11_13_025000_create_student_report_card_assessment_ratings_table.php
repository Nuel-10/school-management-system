<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_report_card_assessment_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_report_card_id')
                ->constrained('student_report_cards', 'id', 'scr_rating_card_fk')
                ->cascadeOnDelete();

            $table->foreignId('report_card_assessment_item_id')
                ->constrained('report_card_assessment_items', 'id', 'scr_rating_item_fk')
                ->restrictOnDelete();

            $table->decimal('rating', 5, 2)->nullable();
            $table->string('remark')->nullable();

            $table->timestamps();

            $table->unique(
                ['student_report_card_id', 'report_card_assessment_item_id'],
                'report_card_assessment_rating_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_report_card_assessment_ratings');
    }
};