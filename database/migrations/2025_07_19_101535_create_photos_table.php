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
        if (Schema::hasTable('photos')) {
            return; // If the table already exists, skip the migration
        }
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('url')->comment('URL of the photo');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        if (!Schema::hasTable('photos')) {
            return; // If the table does not exist, skip the rollback
        }
        Schema::dropIfExists('photos');
    }
};
