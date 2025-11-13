<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Repositories\ClubRepository;
use App\Repositories\FavoriteCostumeRepository;
use App\Repositories\TrooperRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles the submission for adding a new favorite costume via an HTMX request.
 */
class FavoriteCostumesSubmitHtmxController extends Controller
{
    /**
     * @param TrooperRepository $troopers The trooper repository.
     * @param ClubRepository $clubs The club repository.
     * @param FavoriteCostumeRepository $favorites The favorite costume repository.
     */
    public function __construct(
        private readonly TrooperRepository $troopers,
        private readonly ClubRepository $clubs,
        private readonly FavoriteCostumeRepository $favorites)
    {
    }

    /**
     * Handle the incoming request to add a favorite costume.
     *
     * This method validates that the user is a member of the club associated with the costume
     * before adding it to their favorites list. It then returns a view partial containing
     * the updated list of favorite costumes.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered favorite costumes view, intended for an HTMX response.
     */
    public function __invoke(Request $request): View
    {
        $trooper = $this->troopers->getById(Auth::user()->id);

        $club_id = (int) $request->input('club_id', -1);
        $costume_id = (int) $request->input('costume_id', -1);

        if ($club_id > -1 && $costume_id > -1)
        {
            $ids = $trooper->getAassignedClubIds();

            if (in_array($club_id, $ids))
            {
                $club = $this->clubs->getById($club_id);

                if (isset($club))
                {
                    $costume = $club->costumes()->firstWhere('id', $costume_id);

                    if (isset($costume))
                    {
                        $this->favorites->add($trooper->id, $costume->id);
                    }
                }
            }
        }

        $data = [
            'clubs' => collect(),
            'selected_club' => null,
            'costumes' => collect(),
            'favorites' => $this->favorites->getForTrooper($trooper, true),
        ];

        return view('pages.account.favorite-costumes', $data);
    }
}
