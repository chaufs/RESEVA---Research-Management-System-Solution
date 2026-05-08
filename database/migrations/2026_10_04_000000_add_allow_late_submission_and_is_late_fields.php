<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('allow_late_submission')->default(false)->after('max_submissions');
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->boolean('is_late')->default(false)->after('submission_count');
        });
    }

    public function down(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropColumn('is_late');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('allow_late_submission');
        });
    }
};