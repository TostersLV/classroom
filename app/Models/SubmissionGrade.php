<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionGrade extends Model
{
    protected $table = 'submission_grades';

    protected $fillable = [
        'submit_task_id',
        'grader_id',
        'grade',
        'feedback',
        'graded_at',
    ];

    public function submission()
    {
        return $this->belongsTo(SubmitTask::class, 'submit_task_id');
    }

    public function grader()
    {
        return $this->belongsTo(\App\Models\User::class, 'grader_id');
    }
}
