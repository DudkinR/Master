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
        if (Schema::hasTable('task_actions')) {
            return;
        }
        /*'start', початок завдання
                'start_day', початок робочого дня
                'pause', призупинення завдання
                'start_work', початок роботи
                'end_day', кінець робочого дня
                'complete', завершення завдання
                'issue', проблема з завданням
                'missing_resource', відсутній ресурс
                'machine_start', початок роботи машини
                'machine_pause', призупинення роботи машини
                'machine_end'    , кінець роботи машини       */
        Schema::create('task_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('machine_id')->nullable(); // ID машини, якщо застосовується
            $table->enum('action_type', [
                'start',
                'start_day',
                'pause',
                'start_work',
                'end_day',
                'complete',
                'issue',
                'missing_resource',
                'machine_start',
                'machine_pause',
                'machine_end'                 
            ]);
            $table->text('comment')->nullable(); // примітки або аналіз
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('task_actions')) {
            return;
        }
        Schema::dropIfExists('task_actions');
    }
};
