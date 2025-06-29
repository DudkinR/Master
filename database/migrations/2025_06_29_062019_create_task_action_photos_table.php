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
        if (Schema::hasTable('task_action_photos')) {
            return; // If the table already exists, skip the creation
        }
        /*
          protected $table = 'task_action_photos';

    protected $fillable = [
        'log_id',
        'file_path',
    ];
*/
        Schema::create('task_action_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_id')
                ->constrained('task_action_logs')
                ->onDelete('cascade'); // Cascade delete if the log is deleted
            $table->string('file_path'); // Path to the photo file

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('task_action_photos')) {
            return; // If the table does not exist, skip the drop
        }
        Schema::dropIfExists('task_action_photos');
    }
};
