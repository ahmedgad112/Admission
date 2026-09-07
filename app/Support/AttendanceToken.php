<?php

namespace App\Support;

class AttendanceToken
{
    public static function fromScannedValue(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $normalized = strtolower((string) preg_replace('/\s+/', '', $value));

        if ($normalized === '') {
            return '';
        }

        $fromQuery = self::tokenFromQuery($normalized);

        return $fromQuery !== '' ? $fromQuery : $normalized;
    }

    private static function tokenFromQuery(string $value): string
    {
        $candidate = $value;

        if (! str_contains($candidate, '://') && str_contains($candidate, '/')) {
            $candidate = 'http://attendance.local'.(str_starts_with($candidate, '/') ? $candidate : '/'.$candidate);
        }

        if (filter_var($candidate, FILTER_VALIDATE_URL) !== false) {
            $query = parse_url($candidate, PHP_URL_QUERY);

            if (is_string($query) && $query !== '') {
                parse_str($query, $params);
                $token = $params['token'] ?? null;

                if (is_string($token) && $token !== '') {
                    return strtolower($token);
                }
            }

            $path = parse_url($candidate, PHP_URL_PATH);

            if (is_string($path) && preg_match('#/q/([A-Za-z0-9]+)/?$#', $path, $matches) === 1) {
                return strtolower($matches[1]);
            }
        }

        if (preg_match('/(?:^|[?&])token=([^&#]+)/', $value, $matches) === 1) {
            return strtolower((string) rawurldecode($matches[1]));
        }

        if (preg_match('#(?:^|/)q/([A-Za-z0-9]+)/?$#', $value, $matches) === 1) {
            return strtolower($matches[1]);
        }

        return '';
    }
}
