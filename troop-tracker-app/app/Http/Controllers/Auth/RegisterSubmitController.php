<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Base\Region;
use App\Models\Organization;
use App\Models\Trooper;
use App\Models\TrooperOrganization;
use App\Models\TrooperRegion;
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

        $membership_role = $data['account_type'] == 'member' ? MembershipRole::Member : MembershipRole::Handler;

        // Loop through selected organizations and assign identifiers
        foreach ($data['organizations'] ?? [] as $organization_id => $club_data)
        {
            if (!empty($club_data['selected']))
            {
                // You’ll need to map organization-specific fields to trooper columns
                // Example: if organization uses 'tkid' as identifier field
                $organization = Organization::find($organization_id);

                if ($organization)
                {
                    $trooper_organization = new TrooperOrganization();

                    $trooper_organization->trooper_id = $trooper->id;
                    $trooper_organization->organization_id = $organization->id;
                    $trooper_organization->identifier = $club_data['identifier'] ?? '';
                    $trooper_organization->notify = true;
                    $trooper_organization->membership_status = MembershipStatus::Pending;
                    $trooper_organization->membership_role = $membership_role;

                    $trooper_organization->save();

                    if (isset($club_data['region_id']))
                    {
                        $region = $organization->regions()->firstWhere(Region::ID, $club_data['region_id']);

                        $trooper_region = new TrooperRegion();

                        $trooper_region->trooper_id = $trooper->id;
                        $trooper_region->region_id = $region->id;
                        $trooper_region->notify = true;
                        $trooper_region->membership_status = MembershipStatus::Pending;
                        $trooper_region->membership_role = $membership_role;

                        $trooper_region->save();

                        if (isset($club_data['unit_id']))
                        {
                            $unit = $region->units()->firstWhere(Unit::ID, $club_data['unit_id']);

                            $trooper_unit = new TrooperUnit();

                            $trooper_unit->trooper_id = $trooper->id;
                            $trooper_unit->unit_id = $unit->id;
                            $trooper_unit->notify = true;
                            $trooper_unit->membership_status = MembershipStatus::Pending;
                            $trooper_unit->membership_role = $membership_role;

                            $trooper_unit->save();
                        }
                    }
                }
            }
        }

        return $trooper;
    }
}