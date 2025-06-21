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
        if (Schema::hasTable('user_department')) {
            return;
        }
        Schema::create('user_department', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users') // Assuming you have a 'users' table
                ->onDelete('cascade');
            $table->foreignId('department_id')
                ->constrained('departments') // Assuming you have a 'departments' table
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('user_department')) {
            return;
        }
        Schema::dropIfExists('user_department');
    }
};
