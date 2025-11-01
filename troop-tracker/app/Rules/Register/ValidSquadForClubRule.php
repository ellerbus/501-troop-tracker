<?php

namespace App\Rules\Register;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Services\SquadService;

class ValidSquadForClubRule implements ValidationRule
{
    public function __construct(private readonly int $club_id)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $squads = app(SquadService::class);

        if ($value == null || !$squads->isActive($value, $this->club_id))
        {
            $fail('Squad selection is invalid.');
        }
    }
}
