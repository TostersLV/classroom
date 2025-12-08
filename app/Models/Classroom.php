<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cover_image',
        'access_code',
        'teacher_id',
    ];

    /**
     * Hide the access_code by default (sensitive data)
     */
    protected $hidden = [
        'access_code',
    ];

    /**
     * Generate a unique random access code for classroom
     * Format: 6 uppercase alphanumeric characters (e.g., ABC123)
     */
    public static function generateAccessCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (self::where('access_code', $code)->exists());

        return $code;
    }

    /**
     * Boot method to automatically generate access code on creation
     */
    protected static function booted()
    {
        static::creating(function ($classroom) {
            if (empty($classroom->access_code)) {
                $classroom->access_code = self::generateAccessCode();
            }
        });
    }

    /**
     * Get the teacher who owns this classroom
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the students enrolled in this classroom
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'classroom_student', 'classroom_id', 'user_id');
    }

    /**
     * Check if the given user is the teacher of this classroom
     */
    public function isTeacher(?User $user = null): bool
    {
        $user = $user ?? auth()->user();
        return $this->teacher_id === $user?->id;
    }

    /**
     * Get the access code only if the user is the teacher
     * Used in API responses or views to conditionally show the code
     */
    public function getAccessCodeForUser(?User $user = null): ?string
    {
        return $this->isTeacher($user) ? $this->access_code : null;
    }

    /**
     * Make access_code visible only to the teacher
     */
    public function toArray()
    {
        $array = parent::toArray();
        
        if (!$this->isTeacher()) {
            unset($array['access_code']);
        }

        return $array;
    }
}
