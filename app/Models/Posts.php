<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Posts extends Model
{
    protected $fillable = [
        'title',
        'author',
        'subject',
        'cover_image',
        'user_id',
    ];
public function comments()
{
    return $this->hasMany(Comment::class, 'post_id', 'id');
}
public function tasks()
{
    return $this->hasMany(Task::class, 'post_id', 'id');
}
}
