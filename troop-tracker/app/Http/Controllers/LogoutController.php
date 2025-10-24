<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use App\Services\FlashMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LogoutController extends Controller
{
    public function __construct(
        private readonly FlashMessageService $flash,
    ) {


    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Auth::logout();

        Session::forget(['id', 'tkid']);

        //  TODO REMOVE WHEN 100%
        if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }

        session_destroy();

        // Forget 'remember me' cookies
        Cookie::queue(Cookie::forget('TroopTrackerUsername'));
        Cookie::queue(Cookie::forget('TroopTrackerPassword'));

        return redirect()->route('login')->withQuery(['logged_out' => '1']);
    }

}
