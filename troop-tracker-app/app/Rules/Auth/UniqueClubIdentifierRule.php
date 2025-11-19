<?php

namespace App\Rules\Auth;

use App\Models\Club;
use App\Models\TrooperClub;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validation rule to ensure a club-specific identifier is unique among troopers of that club.
 *
 * This rule checks the pivot table between troopers and clubs to verify that the
 * provided identifier (e.g., a member ID for a specific club) is not already in use.
 */
class UniqueClubIdentifierRule implements ValidationRule
{
    /**
     * Creates a new rule instance.
     *
     * @param Club $club The club against which the identifier's uniqueness will be checked.
     */
    public function __construct(private readonly Club $club)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute The name of the attribute being validated.
     * @param  mixed  $value The value of the attribute being validated.
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail The closure to call on validation failure.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!empty($value))
        {
            $exists = $this->club->troopers()
                ->wherePivot(TrooperClub::IDENTIFIER, $value)
                ->exists();

            if ($exists)
            {
                $fail("{$this->club->name} ID already exists for .");
            }
        }
    }
}
