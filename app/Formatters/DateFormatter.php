<?php

namespace App\Formatters;

use Carbon\Carbon;

class DateFormatter
{
    /**
     * Format date in a consistent way.
     * 
     * @param string|Carbon|null $date
     * @param string $format
     * @param string $fallback
     * @return string
     */
    public static function format($date, $format = 'Y-m-d', $fallback = '-'): string
    {
        if (empty($date)) {
            return $fallback;
        }

        try {
            if ($date instanceof Carbon) {
                return $date->format($format);
            }

            return Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return $fallback;
        }
    }

    /**
     * Format date for display.
     * 
     * @param string|Carbon|null $date
     * @param string $locale
     * @return string
     */
    public static function formatForDisplay($date, $locale = 'ar'): string
    {
        if (empty($date)) {
            return '-';
        }

        try {
            $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
            
            if ($locale === 'ar') {
                return $carbon->locale('ar')->translatedFormat('Y-m-d');
            }
            
            return $carbon->format('Y-m-d');
        } catch (\Exception $e) {
            return '-';
        }
    }

    /**
     * Format date and time.
     * 
     * @param string|Carbon|null $dateTime
     * @param string $format
     * @return string
     */
    public static function formatDateTime($dateTime, $format = 'Y-m-d H:i'): string
    {
        return self::format($dateTime, $format);
    }
}

