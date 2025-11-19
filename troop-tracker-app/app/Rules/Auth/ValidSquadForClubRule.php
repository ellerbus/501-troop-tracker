<?php

namespace App\Rules\Auth;

use App\Models\Club;
use App\Models\Squad;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validation rule to ensure that a selected squad is valid for a given club.
 *
 * This rule checks if the provided squad ID is associated with the club
 * specified in the constructor.
 */
class ValidSquadForClubRule implements ValidationRule
{
    /**
     * Creates a new rule instance.
     *
     * @param Club $club The club for which the squad validation is being performed.
     */
    public function __construct(private readonly Club $club)
    {
    }

    /**
     * Run the validation rule.
     *
     * Checks if the provided squad ID exists and is active for the given club.
     *
     * @param  string  $attribute The name of the attribute being validated.
     * @param  mixed  $value The value of the attribute (squad ID).
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail The closure to call on validation failure.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!empty($value) && !Squad::active($this->club->id, $value)->exists())
        {
            $fail('Squad selection is invalid.');
        }
    }
}
