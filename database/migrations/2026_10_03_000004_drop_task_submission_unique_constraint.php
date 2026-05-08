<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get all foreign keys on the table
        $foreignKeys = DB::select('SHOW CREATE TABLE task_submissions');
        
        if (!empty($foreignKeys)) {
            $createTable = $foreignKeys[0]->{'Create Table'};
            
            // Find and drop any foreign key referencing the unique index
            preg_match_all('/CONSTRAINT `([^`]+)` FOREIGN KEY/', $createTable, $matches);
            
            if (!empty($matches[1])) {
                foreach ($matches[1] as $fkName) {
                    try {
                        DB::statement("ALTER TABLE task_submissions DROP FOREIGN KEY `$fkName`");
                    } catch (\Exception $e) {
                        // Ignore if it doesn't exist or can't be dropped
                    }
                }
            }
        }
        
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropUnique(['task_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->unique(['task_id', 'student_id']);
        });
    }
};
