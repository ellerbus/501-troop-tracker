<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FlashMessageService;
use Illuminate\Http\Request;

class LoginDisplayController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.login');
    }
}
