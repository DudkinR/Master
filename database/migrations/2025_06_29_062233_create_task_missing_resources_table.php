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
        if (Schema::hasTable('task_missing_resources')) {
            return; // If the table already exists, skip the creation
        }

        Schema::create('task_missing_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_id')
                ->constrained('task_action_logs')
                ->onDelete('cascade'); // Cascade delete if the log is deleted
            $table->string('resource_type'); // 'material' or 'equipment'
            // id or 'material' or 'equipment'
            $table->unsignedBigInteger('resource_id'); // ID з таблиці materials або equipments
            $table->integer('quantity'); // Quantity of the missing resource
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_missing_resources');
    }
};
