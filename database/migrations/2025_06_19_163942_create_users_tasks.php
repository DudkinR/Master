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
        if (Schema::hasTable('users_tasks')) {
            Schema::dropIfExists('users_tasks');
        }
        Schema::create('users_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('worker'); // Default role is 'worker'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users_tasks')) {
            Schema::table('users_tasks', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['task_id']);
            });
        }
        Schema::dropIfExists('users_tasks');
    }
};
