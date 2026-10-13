<?php

namespace App\Traits;

use App\Models\Reg\Faculty;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

trait HasFacultyScope
{
    /**
     * Scope the query to only include records from the user's faculties
     */
    public static function scopeFacultyQuery(Builder $query): Builder
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $query;
        }

        return $query->whereIn('faculty_code', $user->getFacultyCodes());
    }


    /**
     * Determine if faculty-related fields should be shown
     */
    public static function shouldShowFacultyFields(): bool
    {
        $user = Auth::user();

        return $user->hasRole('admin') || $user->faculties()->exists();
    }

    /**
     * Get faculty options for the current user
     */
    public static function getUserFacultyOptions(): array
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return Faculty::all()->pluck('name', 'code')->toArray();
        }

        return $user->faculties()->pluck('name', 'code')->toArray();
    }
}
