<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Contracts\AuthenticationInterface;
use App\Services\FlashMessageService;
use App\Services\TrooperService;
use Illuminate\Http\RedirectResponse;

class RegisterSubmitController extends Controller
{
    public function __construct(
        private readonly AuthenticationInterface $auth,
        private readonly FlashMessageService $flash,
        private readonly TrooperService $troopers
    ) {
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Attempt forum login
        $forumLogin = $request->attemptForumLogin($data['forumid'], $data['forumpassword']);

        if (!($forumLogin['success'] ?? false))
        {
            return response()->json(['error' => 'Forum login failed'], 422);
        }

        // Normalize phone
        $phone = preg_replace('/\D/', '', cleanInput($data['phone'] ?? ''));

        // Hash password
        $hashedPassword = bcrypt($data['forumpassword']);

        // Determine permissions
        $p501 = in_array($data['squad_request'], config('troopers.valid_squad_ids')) ? $data['accountType'] : 0;

        // Prepare insert payload
        $trooperPayload = [
            'user_id' => $forumLogin['user']['user_id'],
            'name' => $data['name'],
            'tkid' => $data['tkid'] ?? 0,
            'email' => $forumLogin['user']['email'],
            'forum_id' => $data['forumid'],
            'p501' => $p501,
            'phone' => $phone,
            'squad' => $data['squad_request'],
            'password' => $hashedPassword,
        ];

        // Create trooper
        $trooper = $this->troopRepo->createTrooper($trooperPayload);

        // Handle club memberships
        foreach (config('troopers.clubs') as $club => $club_value)
        {
            $clubField = $club_value['db3'] ?? null;
            $membership = 0;

            if ($clubField && ($request->filled($clubField) || is_numeric($request->input($clubField))))
            {
                $membership = $data['accountType'];
            }

            if ($club_value['squadID'] === $data['squad_request'])
            {
                $membership = $data['accountType'];
            }

            if (!empty($club_value['db']))
            {
                $this->troopRepo->updateTrooperField($trooper->id, $club_value['db'], $membership);
            }

            if ($clubField)
            {
                $this->troopRepo->updateTrooperField($trooper->id, $clubField, $request->input($clubField) ?? 0);
            }
        }

        return response()->json([
            'message' => 'Request submitted! You will receive an email when your request is approved or denied.',
            'trooper_id' => $trooper->id,
        ]);
    }
}