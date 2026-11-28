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
        Schema::create('course_favorites', function (Blueprint $table) {
            $table->foreignId('user_id')
            ->constrained();
            $table->foreignId('course_id')
            ->constrained();
            $table->primary(['user_id','course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_favorites');
    }
};
