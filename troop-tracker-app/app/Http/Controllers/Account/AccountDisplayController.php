<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Displays the main account management page.
 */
class AccountDisplayController extends Controller
{
    /**
     * Handle the incoming request to display the account page.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered account page view.
     */
    public function __invoke(Request $request): View
    {
        return view('pages.account.display');
    }
}
