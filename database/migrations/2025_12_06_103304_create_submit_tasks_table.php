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
        Schema::create('submit_tasks', function (Blueprint $table) {
            $table->id();
            $table->fogeignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->string('file_name')->nullable();   // original filename
            $table->string('file_path')->nullable();   // storage path (e.g. task-files/xxx.pdf)
            $table->string('file_mime')->nullable();
            $table->unsignedInteger('file_size')->nullable(); // bytes
            // vel vajag izdarit php artisan migrate (neizdariju)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submit_tasks');
    }
};
