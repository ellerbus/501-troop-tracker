<?php

declare(strict_types=1);

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

if (!function_exists('setting'))
{
    /**
     * Retrieve a setting value from the database with optional default and type casting.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        $cast = function (mixed $value): mixed
        {
            if (is_null($value))
            {
                return null;
            }

            if (is_numeric($value))
            {
                return str_contains($value, '.') ? (float) $value : (int) $value;
            }

            $lower = strtolower((string) $value);

            return match ($lower)
            {
                'true', '(true)' => true,
                'false', '(false)' => false,
                'null', '(null)' => null,
                default => $value,
            };
        };

        $value = Cache::rememberForever("setting.{$key}", fn() =>
            Setting::find($key)?->value
        );

        return $cast($value ?? $default);

    }
}
