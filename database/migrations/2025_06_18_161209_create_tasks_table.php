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
        if (Schema::hasTable('tasks')) {
            return;
        }
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            //order in project
            $table->unsignedInteger('order')->default(0)->comment('Порядок виконання завдання в проєкті');
            $table->string('name')->nullable(false);
            $table->text('description')->nullable();
            $table->date('start_date')->nullable()->comment('Орієнтовна дата початку завдання');
            $table->date('end_date')->nullable()->comment('Орієнтовна дата завершення завдання');            
            $table->decimal('estimated_hours', 8, 2)->default(0)->comment('Орієнтовна кількість годин на виконання завдання');
            $table->decimal('actual_hours', 8, 2)->default(0)->comment('Фактична кількість годин, витрачених на виконання завдання');            
            $table->decimal('progress', 5, 2)->default(0)->comment('Прогрес виконання завдання у відсотках');            
            $table->decimal('quantity', 10, 2)->default(0)->comment('Кількість одиниць завдання (наприклад, м2, шт)');
            $table->string('unit')->default('шт')->comment('Одиниця виміру завдання (наприклад, м2, шт)');            
            $table->unsignedBigInteger('project_id')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('tasks')) {
            return;
        }
        Schema::dropIfExists('tasks');
    }
};
