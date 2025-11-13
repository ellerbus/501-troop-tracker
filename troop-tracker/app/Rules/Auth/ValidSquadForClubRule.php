<?php

namespace App\Rules\Auth;

use App\Models\Club;
use App\Repositories\SquadRepository;
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
            $squads = app(SquadRepository::class);

            if ($squads->isNotActive($value, $this->club->id))
            {
                $fail('Squad selection is invalid.');
            }
        }
    }
}
