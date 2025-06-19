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
        if (Schema::hasTable('tasks_equipments')) {
            Schema::dropIfExists('tasks_equipments');
        }
        Schema::create('tasks_equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');
            $table->decimal('quantity', 10, 2)->default(0); // Quantity of the equipment used in the task
            $table->string('unit')->default('шт'); // Unit of measurement for the equipment, default is 'шт' (pieces)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('tasks_equipments')) {
            return;
        }
        Schema::dropIfExists('tasks_equipments');

    }
};
