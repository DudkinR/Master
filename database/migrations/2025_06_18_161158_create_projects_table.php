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
        if (Schema::hasTable('projects')) {
            return;
        }
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false)->comment('Назва проєкту');
            $table->string('location')->nullable()->comment('Місце реалізації (локація)');
            $table->string('manager')->nullable()->comment('Керівник проєкту');
            $table->date('start_date')->nullable()->comment('Орієнтовна дата початку');
            $table->date('end_date')->nullable()->comment('Орієнтовна дата завершення');
            $table->decimal('budget', 15, 2)->nullable()->comment('Загальний кошторис (грн)');
            $table->text('description')->nullable()->comment('Короткий опис проєкту');
            $table->string('status')->default('planned')->comment('Статус проєкту (наприклад, запланований, в процесі, завершений)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('projects')) {
            return;
        }
        Schema::dropIfExists('projects');
    }
};
