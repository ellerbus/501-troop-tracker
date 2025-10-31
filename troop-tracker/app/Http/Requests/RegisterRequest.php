<?php

namespace App\Http\Requests;

use App\Rules\RegisterWithAtLeastOneClubRule;
use App\Rules\ValidSquadForClubRule;
use App\Services\ClubService;
use Illuminate\Foundation\Http\FormRequest;

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
            'phone' => ['nullable', 'string', 'max:10'],
            'account_type' => ['required', 'in:1,4'],
            'forum_username' => ['required', 'string'],
            'forum_password' => ['required', 'string'],
            'clubs' => ['array', new RegisterWithAtLeastOneClubRule()],
            'clubs.*.selected' => ['nullable', 'boolean'],
        ];

        return array_merge($rules, $this->getClubValidationRules());
    }

    // public function messages(): array
    // {
    //     return [
    //         'clubs.*.identifier.max' => 'Club identifiers must be under 255 characters.',
    //     ];
    // }

    private function getClubValidationRules(): array
    {
        $rules = [];

        $clubs = app(ClubService::class);

        $active_clubs = $clubs->findAllActive(true);

        foreach ($active_clubs as $club)
        {
            if (!empty($club->identifier_validation))
            {
                $required = "|required_if:clubs.{$club->id}.selected,1";

                $validation = $club->identifier_validation . $required;

                $rules["clubs.{$club->id}.identifier"] = explode('|', $validation);

                if ($club->squads->count() > 0)
                {
                    $rules["clubs.{$club->id}.squad_id"] = [new ValidSquadForClubRule($club->id)];
                }
            }
        }

        return $rules;
    }
}