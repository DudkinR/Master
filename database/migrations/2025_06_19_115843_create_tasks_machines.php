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
        if (Schema::hasTable('tasks_machines')) {
            Schema::dropIfExists('tasks_machines');
        }
        Schema::create('tasks_machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('machine_id')->constrained('machines')->onDelete('cascade');
            $table->decimal('quantity', 10, 2)->default(0); // Quantity of the machine used in the task
            $table->string('unit')->default('шт'); // Unit of measurement for the machine, default is 'шт' (pieces)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('tasks_machines')) {
            return;
        }
        Schema::dropIfExists('tasks_machines');
    }
};
