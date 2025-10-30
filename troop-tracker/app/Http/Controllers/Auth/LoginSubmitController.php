<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AuthenticationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Trooper;
use App\Services\ClubService;
use App\Services\FlashMessageService;
use App\Contracts\AuthenticationInterface;
use App\Services\TrooperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Enums\MembershipStatus;

class LoginSubmitController extends Controller
{
    public function __construct(
        private readonly FlashMessageService $flash,
        private readonly AuthenticationInterface $auth,
        private readonly ClubService $clubs,
        private readonly TrooperService $troopers,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request): RedirectResponse
    {
        //  trooper existance is checked via LoginRequest
        $trooper = $this->troopers->getByForumUsername($request->username);

        if ($trooper->isUnapproved())
        {
            $this->flash->warning('Your access has not been approved yet. Please refer to command staff for additional information.');

            return back()->withErrors(['username' => 'Refer to command staff']);
        }

        $results = $this->auth->authenticate(
            username: $request->username,
            password: $request->password
        );

        if ($results == AuthenticationStatus::BANNED)
        {
            $this->flash->danger('You are currently banned. Please refer to command staff for additional information.');

            return back()->withErrors(['username' => 'Refer to command staff']);
        }

        if ($trooper->permissions == MembershipStatus::Retired)
        {
            //  retired
            $this->flash->danger('You cannot access this account. Please refer to command staff for additional information.');

            return back()->withErrors(['username' => 'You cannot access this account.']);
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

    private function hasClubAssignment(Trooper $trooper): bool
    {
        //  TODO TABLE
        $clubs = $this->clubs->findAllActive();

        foreach ($clubs as $club)
        {
            $status_field = $club->db_status_field;

            if ($trooper->{$status_field} != MembershipStatus::Retired && $trooper->{$status_field} != MembershipStatus::None)
            {
                return true;
            }
        }

        return false;
    }
}
