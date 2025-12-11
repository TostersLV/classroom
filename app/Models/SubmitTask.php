<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmitTask extends Model
{
    protected $table = 'submit_tasks';

    // allow mass assignment if not already set
    protected $fillable = [
        'user_id',
        'task_id',
        'file_name',
        'file_path',
        'file_mime',
        'file_size',
        'message',
    ];

    // relation to the submitting user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // one-to-one relation to the grade record
    public function grade()
    {
        return $this->hasOne(\App\Models\SubmissionGrade::class, 'submit_task_id');
    }

    // convenience accessor (optional)
    public function getGradeValueAttribute()
    {
        return $this->grade?->grade;
    }
}
