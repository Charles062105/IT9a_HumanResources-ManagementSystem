<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format time consistently in 12-hour format with AM/PM
     */
    public static function formatTime(?Carbon $time, $format = 'h:i A'): string
    {
        if (! $time) {
            return '—';
        }

        return $time->format($format);
    }

    /**
     * Format time for display in tables
     */
    public static function displayTime(?Carbon $time): string
    {
        return self::formatTime($time, 'h:i A');
    }

    /**
     * Format duration in hours and minutes
     */
    public static function formatDuration($hours): string
    {
        if (! $hours) {
            return '—';
        }
        $whole = floor($hours);
        $mins = round(($hours - $whole) * 60);

        return sprintf('%dh %02dm', $whole, $mins);
    }
}
