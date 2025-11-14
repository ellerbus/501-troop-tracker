<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Trooper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles displaying the user profile form via an HTMX request.
 */
class ProfileDisplayHtmxController extends Controller
{
    /**
     * Handle the incoming request to display the user profile form.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered profile form view.
     */
    public function __invoke(Request $request): View
    {
        $trooper = Trooper::findOrFail(Auth::user()->id);

        $data = $trooper->only('name', 'email', 'phone');

        return view('pages.account.profile', $data);
    }
}
