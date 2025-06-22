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
        if (Schema::hasTable('enterprise_tasks')) {
            return;
        }
        Schema::create('enterprise_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprise_id')
                ->constrained('enterprises');
            $table->foreignId('task_id')
                ->constrained('tasks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('enterprise_tasks')) {
            return;
        }
        Schema::dropIfExists('enterprise_tasks');
    }
};
