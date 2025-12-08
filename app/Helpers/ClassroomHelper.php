<?php

namespace App\Helpers;

use App\Models\Classroom;

class ClassroomHelper
{
    /**
     * Generate a unique random access code
     * Format: 6 uppercase alphanumeric characters
     * Example: A1B2C3, XYZ789
     */
    public static function generateAccessCode(): string
    {
        return Classroom::generateAccessCode();
    }

    /**
     * Validate an access code format
     */
    public static function isValidAccessCodeFormat(string $code): bool
    {
        return preg_match('/^[A-Z0-9]{6}$/', $code) === 1;
    }

    /**
     * Find classroom by access code
     */
    public static function findByAccessCode(string $code): ?Classroom
    {
        return Classroom::where('access_code', strtoupper($code))->first();
    }
}
