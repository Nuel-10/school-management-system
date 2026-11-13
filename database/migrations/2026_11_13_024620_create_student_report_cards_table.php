<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_report_cards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('class_arm_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('academic_session_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('term', 50);

            $table->text('class_teacher_comment')->nullable();
            $table->string('class_teacher_name')->nullable();

            $table->text('principal_comment')->nullable();
            $table->string('principal_name')->nullable();

            $table->unsignedSmallInteger('attendance_present')->nullable();
            $table->unsignedSmallInteger('attendance_total')->nullable();

            $table->unsignedSmallInteger('class_position')->nullable();
            $table->date('next_term_begins')->nullable();

            $table->string('status')->default('draft');

            $table->foreignId('prepared_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['student_id', 'academic_session_id', 'term'],
                'student_report_card_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_report_cards');
    }
};