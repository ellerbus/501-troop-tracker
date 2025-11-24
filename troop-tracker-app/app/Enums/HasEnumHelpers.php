<?php

declare(strict_types=1);

namespace App\Enums;

trait HasEnumHelpers
{
    /**
     * Return an associative array of enum case name => value.
     *
     * @return array<string, string>
     */
    public static function toArray(): array
    {
        $pairs = [];

        foreach (self::cases() as $case)
        {
            $pairs[$case->value] = $case->name;
        }

        return $pairs;
    }

    /**
     * Return an comma delimited string.
     *
     * @return string
     */
    public static function toValidator(): string
    {
        $values = [];

        foreach (self::cases() as $case)
        {
            $values[] = $case->value;
        }

        return implode(',', $values);
    }
}