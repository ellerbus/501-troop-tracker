<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Organization;
use App\Models\Trooper;
use App\Models\TrooperOrganization;
use App\Models\TrooperUnit;
use App\Models\Unit;
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

        // Loop through selected organizations and assign identifiers
        foreach ($data['organizations'] ?? [] as $club_id => $club_data)
        {
            if (!empty($club_data['selected']))
            {
                // You’ll need to map organization-specific fields to trooper columns
                // Example: if organization uses 'tkid' as identifier field
                $organization = Organization::find($club_id);

                if ($organization && !empty($club_data['identifier']))
                {
                    $trooper->organizations()->attach($organization->id, [
                        TrooperOrganization::IDENTIFIER => $club_data['identifier'],
                        TrooperOrganization::NOTIFY => true,
                        TrooperOrganization::STATUS => $status,
                    ]);
                }

                if (isset($club_data['squad_id']))
                {
                    $unit = $organization->units()->firstWhere(Unit::ID, $club_data['squad_id']);

                    $trooper->units()->attach($unit->id, [
                        TrooperUnit::NOTIFY => true,
                        TrooperUnit::STATUS => $status,
                    ]);
                }
            }
        }

        return $trooper;
    }
}