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
       DB::statement("ALTER TABLE task_actions 
        MODIFY COLUMN action_type ENUM(
            'start', 'start_day', 'paused', 'start_work', 
            'end_day', 'complete', 'issue', 'missing_resource', 
            'machine_start', 'machine_pause', 'machine_end'
        ) DEFAULT 'start'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
