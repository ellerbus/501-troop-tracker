<?php

namespace App\Http\Controllers;

use App\Enums\AuthenticationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\FlashMessageService;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Trooper;
use App\Enums\MembershipStatus;

class LoginSubmitController extends Controller
{
    public function __construct(
        private readonly FlashMessageService $flash,
        private readonly AuthenticationService $auth,
    ) {


    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        //  trooper existance is checked via LoginRequest
        $trooper = Trooper::where('forum_id', $request->username)->first();

        if ($trooper->approved == false)
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

        if ($trooper->permissions == 3)
        {
            //  retired
            $this->flash->danger('You cannot access this account. Please refer to command staff for additional information.');

            return back()->withErrors(['username' => 'You cannot access this account.']);
        }

        $can_access = false;

        if ($results == AuthenticationStatus::SUCCESS)
        {

            if ($trooper->p501 != MembershipStatus::Retired && $trooper->p501 != MembershipStatus::None)
            {
                $can_access = true;
            }

            //  TODO TABLE
            $clubs = ['pRebel', 'pDroid', 'pMando', 'pOther', 'pSG', 'pDE'];
            foreach ($clubs as $club)
            {
                if ($trooper->{$club} != MembershipStatus::Retired && $trooper->{$club} != MembershipStatus::None)
                {
                    $can_access = true;
                }
            }

            if ($can_access)
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
        }

        //  no idea but don't let them in
        return back()->withErrors(['username' => 'Invalid credentials']);
    }

}
