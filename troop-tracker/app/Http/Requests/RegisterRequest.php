<?php

namespace App\Http\Requests;

use App\Rules\RegisterWithAtLeastOneClubRule;
use App\Rules\ValidSquadForClubRule;
use App\Rules\UniqueClubIdentifierRule;
use App\Services\ClubService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:240'],
            'phone' => ['nullable', 'string', 'max:10'],
            'account_type' => ['required', 'in:1,4'],
            'forum_username' => [
                'required',
                'string',
                Rule::unique('troopers', 'forum_id'),
            ],
            'forum_password' => ['required', 'string'],
        ];

        return array_merge($rules, $this->getClubValidationRules());
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone'))
        {
            $phone = $this->input('phone');

            $this->merge([
                'phone' => preg_replace('/\D+/', '', $phone),
            ]);
        }
    }


    // public function messages(): array
    // {
    //     return [
    //         'clubs.*.identifier.max' => 'Club identifiers must be under 255 characters.',
    //     ];
    // }

    private function getClubValidationRules(): array
    {
        $rules = [
            'clubs' => ['array', new RegisterWithAtLeastOneClubRule()],
            'clubs.*.selected' => ['nullable', 'boolean'],
        ];

        $clubs = app(ClubService::class);

        $active_clubs = $clubs->findAllActive(true);

        foreach ($active_clubs as $club)
        {
            if (!empty($club->identifier_validation))
            {
                $club_rules = explode('|', $club->identifier_validation);

                $club_rules[] = "required_if:clubs.{$club->id}.selected,1";
                $club_rules[] = new UniqueClubIdentifierRule($club->id);

                $rules["clubs.{$club->id}.identifier"] = $club_rules;

                if ($club->squads->count() > 0)
                {
                    $rules["clubs.{$club->id}.squad_id"] = [new ValidSquadForClubRule($club->id)];
                }
            }
        }

        return $rules;
    }
}