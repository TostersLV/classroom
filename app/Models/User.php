<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Models\Comment;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'profile_picture',
    ];

   public function getProfilePictureUrlAttribute(): ?string
    {
        if (empty($this->profile_picture)) {
            return null;
        }

        // Prefer Storage URL if the file exists on the public disk
        if (Storage::disk('public')->exists($this->profile_picture)) {
            return Storage::url($this->profile_picture);
        }

        // fallback to asset path (handles legacy values)
        return asset('storage/' . ltrim($this->profile_picture, '/'));
    }

    public function comments()
{
    return $this->hasMany(Comment::class);
}

    // users -> joined classrooms
    public function joinedPosts()
    {
        return $this->belongsToMany(Posts::class, 'post_user', 'user_id', 'post_id')
                    ->withTimestamps();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
