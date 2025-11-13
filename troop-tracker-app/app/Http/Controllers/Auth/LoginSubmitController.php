<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Enums\AuthenticationStatus;
use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Club;
use App\Models\Trooper;
use App\Models\TrooperClub;
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
            $this->flash->danger('You cannot access this account. Please refer to command staff for additional information (retired).');

            return back()
                ->withInput(request()->except('password'))
                ->withErrors(['username' => 'You cannot access this account.']);
        }

        if ($results == AuthenticationStatus::SUCCESS)
        {
            if ($trooper->hasActiveClubStatus())
            {
                return $this->login($trooper, $request);
            }

            //  retired
            $this->flash->danger('You cannot access this account. Please refer to command staff for additional information (no active clubs).');

            return back()
                ->withInput($request->except('password'))
                ->withErrors(['username' => 'Refer to command staff']);
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
        //  all good
        Auth::login($trooper, $request->remember_me);

        //  TODO fix route
        return redirect()->intended('/index.php');
    }
}
