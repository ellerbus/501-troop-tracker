<?php

namespace App\Http\Requests;

use App\Rules\RegisterWithAtLeastOneClubRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string'],
            'account_type' => ['required', 'in:1,4'],
            'forum_username' => ['required', 'string'],
            'forum_password' => ['required', 'string'],
            'clubs' => ['array', new RegisterWithAtLeastOneClubRule()],
            'clubs.*.selected' => ['nullable', 'boolean'],
            'clubs.*.identifier' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'clubs.*.identifier.max' => 'Club identifiers must be under 255 characters.',
        ];
    }

    // public function rules(): array
    // {
    //     return [
    //         'name' => ['required', 'string'],
    //         'forum_username' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             Rule::unique('troopers', 'forum_id'),
    //         ],
    //         'forum_password' => ['required', 'string'],
    //         'tkid' => ['nullable', 'string', 'max:11'],
    //         'account_type' => ['required', 'integer', Rule::in([1, 4])],
    //         'squad' => ['required', 'string'],
    //         'phone' => ['nullable', 'string', 'regex:/^\d{10,15}$/'],
    //     ];
    // }

    // public function withValidator(Validator $validator): void
    // {
    //     $validator->after(function ($validator)
    //     {
    //         // --------------------------------------------------------------------------------
    //         //  validate forum
    //         $forum_username = $this->input('forum_username');
    //         $forum_password = $this->input('forum_password');
    //         $results = $this->auth->authenticate($forum_username, $forum_password);

    //         if ($results !== AuthenticationStatus::SUCCESS)
    //         {
    //             $validator->errors()->add('forum_username', 'Invalid forum credentials.');
    //         }

    //         // --------------------------------------------------------------------------------
    //         //  validate TKID
    //         $tkid = $this->input('tkid', 0);
    //         $account_type = (int) $this->input('account_type');
    //         $squad = $this->input('squad');
    //         $validSquadIDs = config('troopers.valid_squad_ids'); // Load from config or service

    //         // TKID rules
    //         if (in_array($squad, $validSquadIDs))
    //         {
    //             if (strlen($tkid) > 11)
    //             {
    //                 $validator->errors()->add('tkid', 'TKID must be less than eleven (11) characters.');
    //             }

    //             if (!is_numeric($tkid))
    //             {
    //                 $validator->errors()->add('tkid', 'TKID must be an integer.');
    //             }

    //             if ($account_type === AccountType::Regular && (int) $tkid === 0)
    //             {
    //                 $validator->errors()->add('tkid', 'TKID cannot be zero.');
    //             }

    //             if ($account_type === AccountType::Handler && (int) $tkid > 0)
    //             {
    //                 $validator->errors()->add('tkid', 'TKID must be zero for a handler account.');
    //             }
    //         }

    //         // Forum ID uniqueness
    //         if ($this->forumIdExists($this->input('forumid')))
    //         {
    //             $validator->errors()->add('forumid', 'Forum Name is already taken. Please contact the Webmaster.');
    //         }

    //         // TKID uniqueness for account_type 1
    //         if ($account_type === 1 && $this->tkidExists($tkid, $validSquadIDs))
    //         {
    //             $validator->errors()->add('tkid', 'TKID is taken. If you have troops on the old troop tracker, please request access.');
    //         }

    //         // Forum login check
    //         $forumLogin = $this->attemptForumLogin($this->input('forumid'), $this->input('forumpassword'));
    //         if (!$forumLogin['success'] ?? false)
    //         {
    //             $validator->errors()->add('forumpassword', 'Incorrect Board username and password.');
    //         }
    //         elseif ($this->userIdExists($forumLogin['user']['user_id']))
    //         {
    //             $validator->errors()->add('forumid', 'You already have a Troop Tracker account.');
    //         }

    //         // Club ID uniqueness
    //         foreach (config('troopers.clubs') as $club => $club_value)
    //         {
    //             $clubField = $club_value['db3'] ?? null;
    //             if ($clubField && $this->filled($clubField) && $this->clubIdExists($clubField, $this->input($clubField)))
    //             {
    //                 $validator->errors()->add($clubField, "{$club_value['name']} ID is already taken. Please contact the Webmaster.");
    //             }
    //         }
    //     });
    // }

    // protected function forumIdExists(string $forumid): bool
    // {
    //     return \DB::table('troopers')->where('forum_id', $forumid)->exists();
    // }

    // protected function tkidExists(string $tkid, array $validSquadIDs): bool
    // {
    //     return \DB::table('troopers')
    //         ->where('tkid', $tkid)
    //         ->whereIn('squad', $validSquadIDs)
    //         ->exists();
    // }

    // protected function userIdExists(int $userId): bool
    // {
    //     return \DB::table('troopers')->where('user_id', $userId)->exists();
    // }

    // protected function clubIdExists(string $field, string $value): bool
    // {
    //     return \DB::table('troopers')->where($field, $value)->exists();
    // }

    // protected function attemptForumLogin(string $forumid, string $password): array
    // {
    //     // Replace with actual forum login logic or service
    //     return loginWithForum($forumid, $password);
    // }
    // // protected function prepareForValidation(AuthenticationInterface $auth): void
    // // {
    // //     $this->auth = $auth;
    // // }

}