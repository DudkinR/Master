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
        if (Schema::hasTable('machines')) {
            return;
        }
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->text('description')->nullable()->comment('Опис машини');
            $table->string('type')->default('general')->comment('Тип машини (наприклад, загальна, спеціалізована)');
            $table->decimal('capacity', 10, 2)->default(0)->comment('Потужність машини (наприклад, кВт)');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('machines')) {
            return;
        }
        Schema::dropIfExists('machines');
    }
};
