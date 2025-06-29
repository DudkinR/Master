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
        if (Schema::hasTable('task_action_logs')) {
            return; // If the table already exists, skip the creation
        }
         Schema::create('task_action_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')
                ->constrained('tasks')
                ->onDelete('cascade'); // Cascade delete if the task is deleted
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Cascade delete if the user is deleted
            $table->string('action_type'); // Action type: start, pause, end_day, complete, issue, missing_resource, machine_start, machine_end
            $table->text('comment')->nullable(); // Notes or analysis
            $table->string('location')->nullable(); //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('task_action_logs')) {
            return; // If the table does not exist, skip the drop
        }
        Schema::dropIfExists('task_action_logs');
    }
};
