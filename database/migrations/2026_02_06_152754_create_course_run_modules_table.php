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
        Schema::create('course_run_modules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_run_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('course_module_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unique(['course_run_id', 'course_module_id']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_run_modules');
    }
};
