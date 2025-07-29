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
       // if hasnt column action_type add ? else modify it
        if (!Schema::hasColumn('task_actions', 'action_type')) {
            Schema::table('task_actions', function (Blueprint $table) {
                $table->enum('action_type', [
                    'start', 'start_day', 'paused', 'start_work', 
                    'end_day', 'complete', 'issue', 'missing_resource', 
                    'machine_start', 'machine_pause', 'machine_end'
                ])->default('start');
            });
            return;
        } else
        {
        // Modify the existing action_type column
           DB::statement("ALTER TABLE task_actions 
            MODIFY COLUMN action_type ENUM(
                'start', 'start_day', 'paused', 'start_work', 
                'end_day', 'complete', 'issue', 'missing_resource', 
                'machine_start', 'machine_pause', 'machine_end'
            ) DEFAULT 'start'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasColumn('task_actions', 'action_type')) {
            Schema::table('task_actions', function (Blueprint $table) {
                $table->dropColumn('action_type');
            });
        }
    }
};
