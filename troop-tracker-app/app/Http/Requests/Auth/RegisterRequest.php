<?php

namespace App\Http\Requests\Auth;

use App\Models\Club;
use App\Models\Trooper;
use App\Rules\Auth\AtLeastOneClubSelectedRule;
use App\Rules\Auth\UniqueClubIdentifierRule;
use App\Rules\Auth\ValidSquadForClubRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

/**
 * Handles the validation for the user registration form.
 *
 * This class defines the base validation rules for user registration and dynamically
 * adds rules based on the clubs a user selects, including custom rules for
 * club-specific identifiers and squad selections. It also customizes error messages
 * for a better user experience.
 */
class RegisterRequest extends FormRequest
{
    private ?Collection $active_clubs = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool Returns true as registration is open to guests.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed> The combined validation rules for the registration form.
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:240'],
            'phone' => ['nullable', 'string', 'max:10'],
            'account_type' => ['required', 'in:member,handler'],
            'username' => [
                'required',
                'string',
                Rule::unique('tt_troopers', Trooper::USERNAME),
            ],
            'password' => ['required', 'string'],
        ];

        $rules = array_merge($rules, $this->getClubValidationRules());

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * This method sanitizes the phone number by removing any non-digit characters.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('phone'))
        {
            $this->merge([
                'phone' => preg_replace('/\D+/', '', $this->input('phone')),
            ]);
        }
    }

    /**
     * Generates dynamic validation rules for selected clubs.
     *
     * Fetches active clubs and constructs validation rules for their specific identifiers
     * (e.g., TKID, CAT #) and associated squads, applying custom rule objects.
     *
     * @return array<string, mixed> An array of validation rules for the 'clubs' input.
     */
    private function getClubValidationRules(): array
    {
        $rules = [
            'clubs' => ['array', new AtLeastOneClubSelectedRule()],
            'clubs.*.selected' => ['nullable', 'boolean'],
        ];

        $active_clubs = $this->getActiveClubs();

        foreach ($active_clubs as $club)
        {
            if (!empty($club->identifier_validation))
            {
                $club_rules = explode('|', $club->identifier_validation);

                $club_rules[] = "required_if:clubs.{$club->id}.selected,1";
                $club_rules[] = new UniqueClubIdentifierRule($club);

                $rules["clubs.{$club->id}.identifier"] = $club_rules;

                if ($club->squads->count() > 0)
                {
                    $rules["clubs.{$club->id}.squad_id"] = [
                        "required_if:clubs.{$club->id}.selected,1",
                        new ValidSquadForClubRule($club)
                    ];
                }
            }
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * This method is used to add custom, user-friendly error messages for the
     * dynamically generated club identifier rules.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator($validator): void
    {
        $active_clubs = $this->getActiveClubs();

        $messages = [];

        foreach ($active_clubs as $club)
        {
            $key = "clubs.{$club->id}.identifier";

            if (!empty($club->identifier_validation))
            {
                $rules = explode('|', $club->identifier_validation);

                foreach ($rules as $rule)
                {
                    $ruleName = $this->normalizeRuleKey($rule);

                    $messages["{$key}.{$ruleName}"] = "The {$club->identifier_display} for {$club->name} must be {$this->friendlyPhrase($rule)}.";
                }

                if ($club->squads->count() > 0)
                {
                    $messages["clubs.{$club->id}.squad_id.required_if"] = "Please select a squad for {$club->name} if you’ve chosen it.";
                }

                // Optional: add a message for the required_if rule
                $messages["{$key}.required_if"] = "Please enter your {$club->identifier_display} for {$club->name} if selected.";
            }
        }

        $validator->setCustomMessages($messages);
    }

    private function normalizeRuleKey(string $rule): string
    {
        // Laravel uses 'between' not 'between:1000,9999' for message keys
        return explode(':', $rule)[0];
    }

    private function friendlyPhrase(string $rule): string
    {
        return match ($rule)
        {
            'integer' => 'an integer',
            'string' => 'a valid string',
            default => str_replace(':', ' ', $rule),
        };
    }

    private function getActiveClubs(): Collection
    {
        return $this->active_clubs ??= Club::active(include_squads: true)->get();
    }
}