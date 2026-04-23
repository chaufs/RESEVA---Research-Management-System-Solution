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
        if (! Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id('student_id');
                $table->foreignId('userID')->references('userID')->on('users');
                $table->string('SFname');
                $table->string('SMname')->nullable();
                $table->string('SLname');
                $table->foreignId('program_id')->references('program_id')->on('programs');
                $table->unsignedBigInteger('Group_ID')->nullable();
                $table->timestamps();

                $table->foreign('Group_ID')->references('Group_ID')->on('ResearchGroups')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
