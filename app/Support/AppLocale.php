<?php

namespace App\Support;

class AppLocale
{
    public const English = 'en';

    public const Arabic = 'ar';

    /**
     * @return list<string>
     */
    public static function available(): array
    {
        return [self::English, self::Arabic];
    }

    public static function isSupported(?string $locale): bool
    {
        return in_array($locale, self::available(), true);
    }

    public static function direction(string $locale): string
    {
        return $locale === self::Arabic ? 'rtl' : 'ltr';
    }
}
