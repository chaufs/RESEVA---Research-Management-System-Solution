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
        if (! Schema::hasTable('class_student')) {
            Schema::create('class_student', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_id')->constrained('class');
                $table->unsignedBigInteger('student_id');
                $table->timestamp('assigned_at')->nullable();
                $table->string('role')->nullable();
                $table->timestamps();

                $table->foreign('student_id')->references('student_id')->on('students')->cascadeOnDelete();
                $table->unique(['class_id', 'student_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_student');
    }
};
