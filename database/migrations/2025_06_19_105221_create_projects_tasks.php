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
        if (Schema::hasTable('projects_tasks')) {
            Schema::dropIfExists('projects_tasks');
        }
        Schema::create('projects_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->string('status')->default('pending'); // Status of the task in the project            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('projects_tasks')) {
            Schema::table('projects_tasks', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
                $table->dropForeign(['task_id']);
            });
        }
        Schema::dropIfExists('projects_tasks');
    }
};
