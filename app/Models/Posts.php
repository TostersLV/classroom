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
        'code',
    ];
public function comments()
{
    return $this->hasMany(Comment::class, 'post_id', 'id');
}
public function tasks()
{
    return $this->hasMany(Task::class, 'post_id', 'id');
}
// classroom -> users who joined
    public function joinedUsers()
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id')
                    ->withTimestamps();
    }
}
