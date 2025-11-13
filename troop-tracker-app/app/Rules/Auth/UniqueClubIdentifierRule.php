<?php

namespace App\Rules\Auth;

use App\Models\Club;
use App\Models\TrooperClub;
use App\Repositories\TrooperRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueClubIdentifierRule implements ValidationRule
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
