<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\EventSignUpRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Displays the main  rd management page.
 */
class UpcomingTroopsHtmxController extends Controller
{
    public function __construct(
        private readonly EventSignUpRepository $signups
    ) {
    }

    /**
     * Handle the incoming request to display the dashboard page.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered dashboard page view.
     */
    public function __invoke(Request $request): View
    {
        $trooper_id = (int) $request->get('trooper_id', Auth::user()->id);

        $data = [
            'upcoming_troops' => $this->signups->getUpcomingTroops($trooper_id)
        ];

        return view('pages.dashboard.upcoming-troops', $data);
    }
}
