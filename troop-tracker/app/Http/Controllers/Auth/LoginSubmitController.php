<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Enums\AuthenticationStatus;
use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Trooper;
use App\Repositories\ClubRepository;
use App\Repositories\TrooperRepository;
use App\Services\FlashMessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Handles the submission of the login form, authenticates the user, and manages the session.
 */
class LoginSubmitController extends Controller
{
    /**
     * @param FlashMessageService $flash The flash message service.
     * @param AuthenticationInterface $auth The authentication service.
     * @param ClubRepository $clubs The club repository.
     * @param TrooperRepository $troopers The trooper repository.
     */
    public function __construct(
        private readonly FlashMessageService $flash,
        private readonly AuthenticationInterface $auth,
        private readonly ClubRepository $clubs,
        private readonly TrooperRepository $troopers,
    ) {
    }

    /**
     * Handle the incoming login request.
     *
     * @param LoginRequest $request The validated login form request.
     * @return RedirectResponse A redirect response to the intended page or back with errors.
     */
    public function __invoke(LoginRequest $request): RedirectResponse
    {
        //  trooper existance is checked via LoginRequest
        $trooper = $this->troopers->getByForumUsername($request->username);

        if ($trooper->isUnapproved())
        {
            $this->flash->warning('Your access has not been approved yet. Please refer to command staff for additional information.');

            return back()
                ->withInput(request()->except('password'))
                ->withErrors(['username' => 'Refer to command staff']);
        }

        $results = $this->auth->authenticate(
            username: $request->username,
            password: $request->password
        );

        if ($results == AuthenticationStatus::BANNED)
        {
            $this->flash->danger('You are currently banned. Please refer to command staff for additional information.');

            return back()
                ->withInput(request()->except('password'))
                ->withErrors(['username' => 'Refer to command staff']);
        }

        if ($trooper->permissions == MembershipStatus::Retired)
        {
            //  retired
            $this->flash->danger('You cannot access this account. Please refer to command staff for additional information.');

            return back()
                ->withInput(request()->except('password'))
                ->withErrors(['username' => 'You cannot access this account.']);
        }

        if ($results == AuthenticationStatus::SUCCESS)
        {
            $can_access = $this->hasClubAssignment($trooper);

            if ($can_access)
            {
                return $this->login($trooper, $request);
            }
        }

        //  no idea but don't let them in
        return back()
            ->withInput($request->except('password'))
            ->withErrors(['username' => 'Invalid credentials']);
    }

    /**
     * Logs the trooper in and sets up the session.
     *
     * @param Trooper $trooper The trooper to log in.
     * @param LoginRequest $request The original login request.
     * @return RedirectResponse A redirect response to the intended page.
     */
    private function login(Trooper $trooper, LoginRequest $request): RedirectResponse
    {
        Session::put('id', $trooper->id);
        Session::put('tkid', $trooper->tkid);

        //  TODO REMOVE WHEN 100%
        if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
        $_SESSION['id'] = $trooper->id;
        $_SESSION['tkid'] = $trooper->tkid;

        //  all good
        Auth::login($trooper, $request->remember_me);

        //  TODO fix route
        return redirect()->intended('/index.php');
    }

    /**
     * Checks if the trooper has an active assignment in any club.
     *
     * @param Trooper $trooper The trooper to check.
     * @return bool True if the trooper has an active club assignment, false otherwise.
     */
    private function hasClubAssignment(Trooper $trooper): bool
    {
        //  TODO REMOVE LEGACY
        $clubs = $this->clubs->findAllActive();

        foreach ($clubs as $club)
        {
            $status_field = $club->troopers_status_field;

            if ($trooper->{$status_field} != MembershipStatus::Retired && $trooper->{$status_field} != MembershipStatus::None)
            {
                return true;
            }
        }

        return $trooper->trooperClubs()
            ->whereNotIn('membership_status', [
                MembershipStatus::None,
                MembershipStatus::Retired,
            ])
            ->whereHas('club', function ($query)
            {
                $query->where('active', true);
            })
            ->exists();
    }
}
