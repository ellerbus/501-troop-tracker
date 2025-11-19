<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Trooper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles the deletion of a trooper costume via an HTMX request.
 */
class TrooperCostumesDeleteHtmxController extends Controller
{
    /**
     * Handle the incoming request to delete a trooper costume.
     *
     * This method removes a costume from the user's troopers list based on the provided 'club_costume_id'.
     * It then returns a view partial containing the updated list of trooper costumes.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered trooper costumes view, intended for an HTMX response.
     */
    public function __invoke(Request $request): View
    {
        $trooper = Trooper::findOrFail(Auth::user()->id);

        $club_costume_id = (int) $request->get('club_costume_id', -1);

        if ($club_costume_id > -1)
        {
            $trooper->detachCostume($club_costume_id);
        }

        $data = [
            'clubs' => collect(),
            'selected_club' => null,
            'costumes' => collect(),
            'trooper_costumes' => $trooper->costumes(),
        ];

        return view('pages.account.trooper-costumes', $data);
    }
}
