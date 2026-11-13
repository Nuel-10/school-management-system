
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_result_approval_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_result_id')
                ->constrained('student_results')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('action'); // submitted, approved, unapproved
            $table->string('previous_status')->nullable();
            $table->string('new_status');
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(['student_result_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_result_approval_histories');
    }
};
