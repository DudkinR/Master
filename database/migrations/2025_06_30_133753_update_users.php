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
        //add new columns to the users table position 
        if (!Schema::hasColumn('users', 'position')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('position')->nullable()->after('email'); // Add position column after email
            });
        };
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop the position column from the users table
        if (Schema::hasColumn('users', 'position')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('position');
            });
        }
    }
};
