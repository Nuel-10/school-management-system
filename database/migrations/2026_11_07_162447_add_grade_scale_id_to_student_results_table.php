
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_results', function (Blueprint $table) {
            $table->foreignId('grade_scale_id')
                ->nullable()
                ->after('remark')
                ->constrained('grade_scales')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('student_results', function (Blueprint $table) {
            $table->dropForeign(['grade_scale_id']);
            $table->dropColumn('grade_scale_id');
        });
    }
};
