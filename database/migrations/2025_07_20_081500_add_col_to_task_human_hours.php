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
        if(Schema::hasColumn('tasks', 'actual_human_hours')) {
            return; // Якщо колонка вже існує, нічого не робимо
        }
        Schema::table('tasks', function (Blueprint $table) {
            // decimal(8,2)
            $table->decimal('actual_human_hours', 8, 2)->default(0)->after('actual_hours'); // Додаємо колонку human_hours до таблиці task
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(!Schema::hasColumn('tasks', 'actual_human_hours')) {
            return; // Якщо колонка не існує, нічого не робимо
        }
        Schema::table('tasks', function (Blueprint $table) {
            //
            $table->dropColumn('actual_human_hours'); // Видаляємо колонку human_hours з таблиці task
        });
    }
};
