<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ClubCostume;
use App\Models\Trooper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles displaying the trooper costumes management interface via an HTMX request.
 */
class TrooperCostumesDisplayHtmxController extends Controller
{
    /**
     * Handle the incoming request to display the trooper costumes interface.
     *
     * This method fetches the user's assigned clubs and their trooper costumes.
     * If a 'club_id' is provided in the request, it also fetches the costumes for that specific club.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered trooper costumes view.
     */
    public function __invoke(Request $request): View
    {
        $trooper = Trooper::findOrFail(Auth::user()->id);

        $clubs = $trooper->assignedClubs();

        $selected_club = null;
        $costumes = [];

        if ($request->has('club_id'))
        {
            $selected_club = $clubs->firstWhere(Club::ID, $request->get('club_id'));

            if (isset($selected_club))
            {
                $costumes = $selected_club->club_costumes
                    ->sortBy(ClubCostume::NAME)
                    ->pluck(ClubCostume::NAME, ClubCostume::ID)
                    ->toArray();
            }
        }

        $data = [
            'clubs' => $clubs,
            'selected_club' => $selected_club,
            'costumes' => $costumes,
            'trooper_costumes' => $trooper->costumes(),
        ];

        return view('pages.account.trooper-costumes', $data);
    }
}
