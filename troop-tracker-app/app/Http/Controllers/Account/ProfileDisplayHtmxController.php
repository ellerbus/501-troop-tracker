<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Repositories\TrooperRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles displaying the user profile form via an HTMX request.
 */
class ProfileDisplayHtmxController extends Controller
{
    /**
     * @param TrooperRepository $troopers The trooper repository.
     */
    public function __construct(private readonly TrooperRepository $troopers)
    {
    }

    /**
     * Handle the incoming request to display the user profile form.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered profile form view.
     */
    public function __invoke(Request $request): View
    {
        $trooper = $this->troopers->getById(Auth::user()->id);

        $data = $trooper->only('name', 'email', 'phone');

        return view('pages.account.profile', $data);
    }
}
