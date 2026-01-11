<?php

namespace App\Formatters;

class StatusFormatter
{
    /**
     * Get badge class for status.
     * 
     * @param string $status
     * @param array $statusMap
     * @return string
     */
    public static function getBadgeClass($status, $statusMap = []): string
    {
        $defaultMap = [
            'active' => 'success',
            'inactive' => 'secondary',
            'pending' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            'paid' => 'success',
            'partial' => 'info',
        ];

        $map = array_merge($defaultMap, $statusMap);
        
        return $map[$status] ?? 'secondary';
    }

    /**
     * Format status with badge.
     * 
     * @param string $status
     * @param string $translationKey
     * @param array $statusMap
     * @return string
     */
    public static function formatWithBadge($status, $translationKey = null, $statusMap = []): string
    {
        $badgeClass = self::getBadgeClass($status, $statusMap);
        $text = $translationKey ? __($translationKey . '.' . $status) : $status;
        
        return '<span class="badge bg-' . $badgeClass . '">' . $text . '</span>';
    }
}

