<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Posts;
use App\Models\User;

class Comment extends Model
{
    protected $fillable = ['post_id','user_id', 'author_name', 'content'];

    public function post()
{
    return $this->belongsTo(Posts::class, 'post_id', 'id');
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
