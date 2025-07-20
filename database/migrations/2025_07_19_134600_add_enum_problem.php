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
        // add enum problem enum('open', 'in_progress', 'resolved')+  closed
        DB::statement("ALTER TABLE problems 
            MODIFY COLUMN status ENUM(
                'open', 'in_progress', 'resolved', 'closed'
            ) DEFAULT 'open'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::statement("ALTER TABLE problems
            MODIFY COLUMN status ENUM(
                'open', 'in_progress', 'resolved'
            ) DEFAULT 'open'");
    }
};
