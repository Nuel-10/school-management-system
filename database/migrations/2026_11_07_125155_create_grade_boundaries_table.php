
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_boundaries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('grade_scale_id')
                ->constrained('grade_scales')
                ->cascadeOnDelete();

            $table->string('grade');
            $table->decimal('minimum_percentage', 5, 2);
            $table->decimal('maximum_percentage', 5, 2);
            $table->string('remark');

            $table->unsignedInteger('display_order')->default(1);

            $table->timestamps();

            $table->unique(
                ['grade_scale_id', 'grade'],
                'grade_boundary_scale_grade_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_boundaries');
    }
};
