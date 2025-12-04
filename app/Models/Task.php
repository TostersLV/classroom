<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
    'post_id',
    'user_id',
    'author_name',
    'title',
    'task_description',
    'file_name',
    'file_path',
    'file_mime',
    'file_size',
];

    public function post()
    {
        return $this->belongsTo(Posts::class);
    }
}
