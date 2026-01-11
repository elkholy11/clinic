<?php

namespace App\Formatters;

class CurrencyFormatter
{
    /**
     * Format amount as currency.
     * 
     * @param float|int $amount
     * @param string $currency
     * @param int $decimals
     * @return string
     */
    public static function format($amount, $currency = 'EGP', $decimals = 2): string
    {
        $formatted = number_format((float) $amount, $decimals);
        return $formatted . ' ' . $currency;
    }

    /**
     * Format amount with locale-specific formatting.
     * 
     * @param float|int $amount
     * @param string $locale
     * @return string
     */
    public static function formatWithLocale($amount, $locale = 'ar'): string
    {
        $currency = __('messages.currency', [], $locale) ?? 'EGP';
        return self::format($amount, $currency);
    }
}

