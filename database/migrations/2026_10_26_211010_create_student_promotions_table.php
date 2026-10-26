
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('from_class_arm_id')
                ->nullable()
                ->constrained('class_arms')
                ->nullOnDelete();

            $table->foreignId('to_class_arm_id')
                ->constrained('class_arms')
                ->restrictOnDelete();

            $table->string('academic_session', 20);
            $table->string('term', 30)->nullable();

            $table->enum('decision', [
                'promoted',
                'repeated',
                'transferred',
            ])->default('promoted');

            $table->text('remarks')->nullable();

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(
                ['student_id', 'academic_session'],
                'student_promotions_student_session_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_promotions');
    }
};
