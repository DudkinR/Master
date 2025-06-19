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
        if (Schema::hasTable('tasks_materials')) {
            Schema::dropIfExists('tasks_materials');
        }
        Schema::create('tasks_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->decimal('quantity', 10, 2)->default(0); // Quantity of the material used in the task
            $table->string('unit')->default('шт'); // Unit of measurement for the material, default is 'шт' (pieces)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('tasks_materials')) {
            return;
        }
        Schema::dropIfExists('tasks_materials');
    }
};
