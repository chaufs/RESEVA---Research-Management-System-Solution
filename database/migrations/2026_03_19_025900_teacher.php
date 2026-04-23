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
        if (! Schema::hasTable('teachers')) {
            Schema::create('teachers', function (Blueprint $table) {
                $table->id();
                $table->foreignID('userID')->references('userID')->on('users');
                $table->string('firstname');
                $table->string('Middlename')->nullable();
                $table->string('Lastname');
                $table->foreignId('department_id')->references('department_id')->on('department');
                $table->string('specialization')->nullable();
                $table->enum('qualification', ['Bachelor', 'Master', 'PhD'])->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
