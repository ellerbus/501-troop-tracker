<?php

namespace App\Rules\Auth;

use App\Models\Club;
use App\Models\Squad;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidSquadForClubRule implements ValidationRule
{
    public function __construct(private readonly Club $club)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!empty($value))
        {
            $count = Squad::active($this->club->id, $value)->count() > 0;

            if ($count == 0)
            {
                $fail('Squad selection is invalid.');
            }
        }
    }
}
