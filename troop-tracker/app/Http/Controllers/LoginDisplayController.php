<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FlashMessageService;
use Illuminate\Http\Request;

class LoginDisplayController extends Controller
{
    public function __construct(
        private readonly FlashMessageService $flash
    ) {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if ($request->has('logged_out'))
        {
            $this->flash->success('You have been logged out.');
        }
        return view('pages.login');
    }
}
