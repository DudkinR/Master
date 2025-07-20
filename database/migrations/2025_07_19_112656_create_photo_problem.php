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
        if (!Schema::hasTable('photo_problem')) {
            Schema::create('photo_problem', function (Blueprint $table) {
                $table->id();
                $table->foreignId('photo_id')->constrained('photos')->onDelete('cascade');
                $table->foreignId('problem_id')->constrained('problems')->onDelete('cascade');            
                $table->timestamps();
            });
        };
        if (!Schema::hasTable('photo_task')) {
            Schema::create('photo_task', function (Blueprint $table) {
                $table->id();
                $table->foreignId('photo_id')->constrained('photos')->onDelete('cascade');
                $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');            
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('photo_action')) {
            Schema::create('photo_action', function (Blueprint $table) {
                $table->id();
                $table->foreignId('photo_id')->constrained('photos')->onDelete('cascade');
                $table->foreignId('task_action_log_id')->constrained('task_action_logs')->onDelete('cascade');            
                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('photo_problem')) {
            Schema::dropIfExists('photo_problem');
        }
        if (Schema::hasTable('photo_task')) {
            Schema::dropIfExists('photo_task');
        }
        if (Schema::hasTable('photo_action')) {
            Schema::dropIfExists('photo_action');
        }
    }
};
