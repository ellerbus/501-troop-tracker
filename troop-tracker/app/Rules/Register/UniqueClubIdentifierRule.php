<?php

namespace App\Rules\Register;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Services\TrooperService;

class UniqueClubIdentifierRule implements ValidationRule
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
        $troopers = app(TrooperService::class);

        if ($value == null || !$troopers->isUniqueClubIdentifier($value, $this->club_id))
        {
            $fail('Identifier already exists.');
        }
    }
}
