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
        if (Schema::hasTable('etalons')) {
            return;
        }
        Schema::create('etalons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->text('description')->nullable();
            $table->decimal('estimated_hours', 8, 2)->default(0)->comment('Орієнтовна кількість годин на виконання завдання');
            $table->decimal('actual_hours', 8, 2)->default(0)->comment('Фактична кількість годин, витрачених на виконання завдання');            
            $table->decimal('quantity', 10, 2)->default(0)->comment('Кількість одиниць завдання (наприклад, м2, шт)');
            $table->string('unit')->default('шт')->comment('Одиниця виміру завдання (наприклад, м2, шт)');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('etalons')) {
            return;
        }
        Schema::dropIfExists('etalons');
    }
};
