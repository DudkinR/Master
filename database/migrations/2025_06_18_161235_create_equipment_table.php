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
        if (Schema::hasTable('equipments')) {
            return;
        }
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false)->comment('Назва обладнання');
            $table->text('description')->nullable()->comment('Опис обладнання');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('equipments')) {
            return;
        }
        Schema::dropIfExists('equipments');
    }
};
