<?php

namespace App\Http\Controllers;

use App\Enums\AuthenticationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\FlashMessageService;
use App\Services\XenforoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Models\Trooper;

class LoginSubmitController extends Controller
{
    public function __construct(
        private readonly FlashMessageService $flash,
        private readonly XenforoService $xenforo,
    ) {


    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        //  trooper existance is checked via LoginRequest
        $trooper = Trooper::where('forum_id', $request->username)->first();

        $results = $this->xenforo->authenticate(
            user_name: $request->username,
            password: $request->password,
            trooper: $trooper
        );

        if ($results == AuthenticationStatus::BANNED)
        {
            $this->flash->danger('You are currently banned. Please refer to command staff for additional information.');

            return back()->withErrors(['username' => 'Refer to command staff']);
        }

        if ($results == AuthenticationStatus::SUCCESS)
        {
            Session::put('id', $trooper->id);
            Session::put('tkid', $trooper->tkid);

            if (session_status() === PHP_SESSION_NONE)
            {
                session_start();
            }

            $_SESSION['id'] = $trooper->id;
            $_SESSION['tkid'] = $trooper->tkid;

            //  all good
            Auth::login($trooper, $request->remember_me);

            //  TODO fix route
            return redirect()->intended('/dashboard');
        }

        //  no idea but don't let them in
        return back()->withErrors(['username' => 'Invalid credentials']);
    }

}
