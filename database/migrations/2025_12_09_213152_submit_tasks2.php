<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // only create if table doesn't exist (safe when original migration already ran)
        if (! Schema::hasTable('submit_tasks')) {
            Schema::create('submit_tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
                $table->string('file_name')->nullable();   // original filename
                $table->string('file_path')->nullable();   // storage path (e.g. submissions/xxx.pdf)
                $table->string('file_mime')->nullable();
                $table->unsignedInteger('file_size')->nullable(); // bytes
                $table->text('message')->nullable(); // optional student note
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('submit_tasks');
    }
};