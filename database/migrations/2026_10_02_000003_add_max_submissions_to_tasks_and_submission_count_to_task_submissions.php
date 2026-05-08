<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedInteger('max_submissions')->default(1)->after('file_path');
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->unsignedInteger('submission_count')->default(1)->after('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropColumn('submission_count');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('max_submissions');
        });
    }
};
