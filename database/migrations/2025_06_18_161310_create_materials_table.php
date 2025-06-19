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
        if (Schema::hasTable('materials')) {
            return;
        }
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false)->comment('Назва матеріалу');
            $table->text('description')->nullable()->comment('Опис матеріалу');
            // unit of measurement
            $table->string('unit')->default('шт')->comment('Одиниця виміру матеріалу (наприклад, м2, шт)');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('materials')) {
            return;
        }
        Schema::dropIfExists('materials');
    }
};
