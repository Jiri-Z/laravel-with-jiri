<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Override;

#[Fillable(['name', 'email', 'password', 'role', 'locale'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasLocalePreference, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    #[Override]
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail);
    }

    #[Override]
    public function sendPasswordResetNotification(#[\SensitiveParameter] mixed $token): void
    {
        $this->notify(new ResetPassword($token));
    }

    /** @return HasMany<Course, $this> */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'user_id');
    }

    /** @return HasMany<StepCompletion, $this> */
    public function stepCompletions(): HasMany
    {
        return $this->hasMany(StepCompletion::class);
    }

    /** @return HasMany<StepAnswer, $this> */
    public function stepAnswers(): HasMany
    {
        return $this->hasMany(StepAnswer::class);
    }

    /** @return HasMany<CourseEnrollment, $this> */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /** @return BelongsToMany<Course, $this> */
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')
            ->withPivot('enrolled_at');
    }

    public function preferredLocale(): string
    {
        return $this->locale;
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function isInstructor(): bool
    {
        return $this->role === Role::Instructor;
    }

    public function isStudent(): bool
    {
        return $this->role === Role::Student;
    }

    public function isStaff(): bool
    {
        return $this->isAdmin() || $this->isInstructor();
    }

    public function ownsCourse(Course $course): bool
    {
        return $this->id === $course->user_id;
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }
}
