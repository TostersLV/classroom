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
        if (! Schema::hasTable('submission_grades')) {
            Schema::create('submission_grades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('submit_task_id')->constrained('submit_tasks')->cascadeOnDelete();
                $table->foreignId('grader_id')->constrained('users')->cascadeOnDelete(); // teacher who graded
                $table->decimal('grade', 5, 2)->nullable(); // 0-100 precision
                $table->text('feedback')->nullable();
                $table->timestamp('graded_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_grades');
    }
};
