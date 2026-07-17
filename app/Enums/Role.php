<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Instructor = 'instructor';
    case Student = 'student';

    public function isStaff(): bool
    {
        return $this === self::Admin || $this === self::Instructor;
    }
}
