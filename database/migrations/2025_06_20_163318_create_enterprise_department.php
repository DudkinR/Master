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
        if (Schema::hasTable('enterprise_department')) {
            return;
        }
        Schema::create('enterprise_department', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprise_id')
                ->constrained('enterprise')
                ->onDelete('cascade');
            $table->foreignId('department_id')
                ->constrained('department')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('enterprise_department')) {
            return;
        }
        Schema::dropIfExists('enterprise_department');
    }
};
