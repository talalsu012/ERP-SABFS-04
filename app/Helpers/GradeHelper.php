<?php

namespace App\Helpers;

use App\Models\Result\Grade;

class GradeHelper
{
    protected static $gradesCache = [];

    /**
     * Initialize the grades cache for a specific plan
     */
    protected static function initializeGradesCache(string $plan): void
    {
        if (!isset(self::$gradesCache[$plan])) {
            self::$gradesCache[$plan] = Grade::where('plan', $plan)
                ->orderBy('upper', 'desc')
                ->get();
        }
    }

    /**
     * Get grade title based on mark and plan
     */
    public static function getGradeTitle(float $mark, string $plan = 'Bachelor'): ?Grade
    {
        self::initializeGradesCache($plan);

        return self::$gradesCache[$plan]->first(function($grade) use ($mark) {
            return $mark >= $grade->lower && $mark <= $grade->upper;
        });
    }

    /**
     * Get grade point based on mark and plan
     */
    public static function getGradePoint(float $mark, string $plan = 'Bachelor'): float
    {
        $grade = self::getGradeTitle($mark, $plan);
        return $grade ? $grade->point : 0.0;
    }

    /**
     * Get all grades for a specific plan
     */
    public static function getGradesForPlan(string $plan): array
    {
        self::initializeGradesCache($plan);
        return self::$gradesCache[$plan]->all();
    }

    /**
     * Check if a mark is passing for a specific plan
     */
    public static function isPassingGrade(float $mark, string $plan = 'Bachelor'): bool
    {
        $grade = self::getGradeTitle($mark, $plan);
        return $grade ? $grade->point > 0 : false;
    }

    /**
     * Clear the cache for a specific plan or all plans
     */
    public static function clearCache(?string $plan = null): void
    {
        if ($plan) {
            unset(self::$gradesCache[$plan]);
        } else {
            self::$gradesCache = [];
        }
    }
}
