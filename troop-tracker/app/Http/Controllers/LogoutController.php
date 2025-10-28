<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
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
    public function __invoke(Request $request): RedirectResponse
    {
        $this->flash->success('You have been logged out.');

        Auth::logout();

        Session::forget(['id', 'tkid']);

        //  TODO REMOVE WHEN 100%
        if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }

        unset($_SESSION['id']);
        unset($_SESSION['tkid']);

        // Forget 'remember me' cookies
        Cookie::queue(Cookie::forget('TroopTrackerUsername'));
        Cookie::queue(Cookie::forget('TroopTrackerPassword'));

        return redirect()->route('login', ['logged_out' => '1']);
    }

}
