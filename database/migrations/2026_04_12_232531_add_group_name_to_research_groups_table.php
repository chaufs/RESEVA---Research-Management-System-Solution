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
        if (Schema::hasTable('ResearchGroups') && ! Schema::hasColumn('ResearchGroups', 'Group_Name')) {
            Schema::table('ResearchGroups', function (Blueprint $table) {
                $table->string('Group_Name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('ResearchGroups') && Schema::hasColumn('ResearchGroups', 'Group_Name')) {
            Schema::table('ResearchGroups', function (Blueprint $table) {
                $table->dropColumn('Group_Name');
            });
        }
    }
};
