<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('timetable_entries', function (Blueprint $table) {
        $table->dropUnique(['day', 'period']);

        $table->dropColumn(['day', 'period', 'title']);

        $table->foreignId('course_id')
              ->constrained('courses');

        $table->foreignId('user_id')
              ->constrained('users')
              ->cascadeOnDelete();

        $table->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
               Schema::table('timetable_entries', function (Blueprint $table) {
               $table->dropUnique(['user_id', 'course_id']);

        $table->dropConstrainedForeignId('user_id');
        $table->dropConstrainedForeignId('course_id');

        $table->smallInteger('day');
        $table->smallInteger('period');
        $table->string('title');

        $table->unique(['day', 'period']);
        });
    }
};
