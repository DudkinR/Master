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
        //  `enterprise` `location` nullable
        if (Schema::hasTable('enterprise')) {
            Schema::table('enterprise', function (Blueprint $table) {
                if (Schema::hasColumn('enterprise', 'location')) {
                    $table->string('location')->nullable()->change();
                }
                if (Schema::hasColumn('enterprise', 'description')) {
                    $table->text('description')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
