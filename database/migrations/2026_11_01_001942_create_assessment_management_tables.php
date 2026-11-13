
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_schemes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_section_id')
                ->constrained('school_sections')
                ->cascadeOnDelete();

            $table->foreignId('class_level_id')
                ->constrained('class_levels')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->foreignId('academic_session_id')
                ->constrained('academic_sessions')
                ->cascadeOnDelete();

            $table->string('name');
            $table->decimal('total_marks', 7, 2)->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(
                ['class_level_id', 'subject_id', 'academic_session_id'],
                'assessment_scheme_unique'
            );
        });

        Schema::create('assessment_components', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assessment_scheme_id')
                ->constrained('assessment_schemes')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('component_type')->default('ca');
            $table->decimal('maximum_score', 7, 2);
            $table->unsignedInteger('display_order')->default(1);
            $table->timestamps();
        });

        Schema::create('student_result_scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_result_id')
                ->constrained('student_results')
                ->cascadeOnDelete();

            $table->foreignId('assessment_component_id')
                ->constrained('assessment_components')
                ->restrictOnDelete();

            $table->decimal('score', 7, 2)->default(0);
            $table->timestamps();

            $table->unique(
                ['student_result_id', 'assessment_component_id'],
                'student_result_component_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_result_scores');
        Schema::dropIfExists('assessment_components');
        Schema::dropIfExists('assessment_schemes');
    }
};
