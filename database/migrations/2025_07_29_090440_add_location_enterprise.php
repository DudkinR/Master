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
        //
        if (!Schema::hasColumn('enterprise', 'location')) {
            Schema::table('enterprise', function (Blueprint $table) {
                // "50.32743060628074, 26.653394827229455" default coordinates format
                $table->string('location')
                ->default('50.32743060628074, 26.653394827229455')
                ->nullable(); // Add location column
            });
        };
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasColumn('enterprise', 'location')) {
            Schema::table('enterprise', function (Blueprint $table) {
                $table->dropColumn('location'); // Drop location column
            });
        }
    }
};
