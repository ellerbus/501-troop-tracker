<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Club;
use App\Models\Squad;
use App\Models\Trooper;
use App\Models\TrooperClub;
use App\Models\TrooperSquad;
use App\Services\FlashMessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

/**
 * Handles the submission of the user registration form.
 */
class RegisterSubmitController extends Controller
{
    /**
     * @param AuthenticationInterface $auth The authentication service.
     * @param FlashMessageService $flash The flash message service.
     */
    public function __construct(
        private readonly AuthenticationInterface $auth,
        private readonly FlashMessageService $flash,
    ) {
    }

    /**
     * Handle the incoming registration request.
     *
     * @param RegisterRequest $request The validated registration form request.
     * @return RedirectResponse A redirect response back to the registration page with a status message or errors.
     */
    public function __invoke(RegisterRequest $request): RedirectResponse
    {
        $auth_user_id = $this->auth->verify(
            username: $request->username,
            password: $request->password
        );

        if ($auth_user_id == null)
        {
            return back()
                ->withInput()
                ->withErrors(['username' => 'Invalid Credentials']);
        }

        $this->register($request->validated(), $auth_user_id);

        $this->flash->success('Request submitted successfully! You will receive an e-mail when your request is approved or denied.');

        //  TODO FIX ROUTE TO SOMETHING THAT MAKES SENSE
        return redirect()->route('auth.register');
    }

    private function register(array $data, mixed $auth_user_id): Trooper
    {
        $trooper = new Trooper();

        $trooper->name = $data['name'];
        $trooper->email = $data['email'];
        $trooper->phone = $data['phone'] ?? null;
        $trooper->username = $data['username'];
        $trooper->password = Hash::make($data['password']);

        $trooper->save();

        $status = $data['account_type'] == 'member' ? MembershipStatus::Member : MembershipStatus::Handler;

        // Loop through selected clubs and assign identifiers
        foreach ($data['clubs'] ?? [] as $club_id => $club_data)
        {
            if (!empty($club_data['selected']))
            {
                // You’ll need to map club-specific fields to trooper columns
                // Example: if club uses 'tkid' as identifier field
                $club = Club::find($club_id);

                if ($club && !empty($club_data['identifier']))
                {
                    $trooper->clubs()->attach($club->id, [
                        TrooperClub::IDENTIFIER => $club_data['identifier'],
                        TrooperClub::NOTIFY => true,
                        TrooperClub::STATUS => $status,
                    ]);
                }

                if (isset($club_data['squad_id']))
                {
                    $squad = $club->squads()->firstWhere(Squad::ID, $club_data['squad_id']);

                    $trooper->squads()->attach($squad->id, [
                        TrooperSquad::NOTIFY => true,
                        TrooperSquad::STATUS => $status,
                    ]);
                }
            }
        }

        return $trooper;
    }
}