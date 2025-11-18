<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EventTrooper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Displays the main  rd management page.
 */
class HistoricalTroopsHtmxController extends Controller
{
    /**
     * Handle the incoming request to display the dashboard page.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered dashboard page view.
     */
    public function __invoke(Request $request): View
    {
        $trooper_id = (int) $request->get('trooper_id', Auth::user()->id);

        $troops = EventTrooper::historicalByTrooper($trooper_id)->get()->sortBy(fn($et) => $et->event->ends_at);

        $data = [
            'historical_troops' => $troops,
        ];

        return view('pages.dashboard.historical-troops', $data);
    }
}
