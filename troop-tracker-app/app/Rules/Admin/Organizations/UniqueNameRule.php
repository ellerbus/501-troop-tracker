<?php

namespace App\Rules\Admin\Organizations;

use App\Models\Organization;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validation rule to ensure an organization-specific identifier is unique among troopers of that organization.
 *
 * This rule checks the pivot table between troopers and organizations to verify that the
 * provided identifier (e.g., a member ID for a specific organization) is not already in use.
 */
class UniqueNameRule implements ValidationRule
{
    /**
     * Creates a new rule instance.
     *
     * @param Organization $organization The organization against which the identifier's uniqueness will be checked.
     */
    public function __construct(
        private bool $is_updating,
        private readonly Organization $organization)
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
            if ($this->is_updating)
            {
                //  updating
                $exists = $this->organization->parent->organizations()
                    ->where(Organization::ID, '!=', $this->organization->id)
                    ->where(Organization::NAME, $value)
                    ->exists();
            }
            else
            {
                //  creating
                $exists = $this->organization->organizations()
                    ->where(Organization::NAME, $value)
                    ->exists();
            }

            if ($exists)
            {
                $fail("{$this->organization->name} Name already exists.");
            }
        }
    }
}
