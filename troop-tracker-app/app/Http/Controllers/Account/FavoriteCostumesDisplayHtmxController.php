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
 * Handles displaying the favorite costumes management interface via an HTMX request.
 */
class FavoriteCostumesDisplayHtmxController extends Controller
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
     * Handle the incoming request to display the favorite costumes interface.
     *
     * This method fetches the user's assigned clubs and their favorite costumes.
     * If a 'club_id' is provided in the request, it also fetches the costumes for that specific club.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered favorite costumes view.
     */
    public function __invoke(Request $request): View
    {
        $trooper = $this->troopers->getById(Auth::user()->id);

        $ids = $trooper->getAassignedClubIds();

        $clubs = $this->clubs->findAllActive(include_costumes: true)->whereIn('id', $ids);

        $selected_club = null;
        $costumes = [];

        if ($request->has('club_id'))
        {
            $selected_club = $clubs->firstWhere('id', $request->get('club_id'));

            if (isset($selected_club))
            {
                $costumes = $selected_club->costumes
                    ->sortBy('costume')
                    ->pluck('costume', 'id')
                    ->toArray();
            }
        }

        $data = [
            'clubs' => $clubs,
            'selected_club' => $selected_club,
            'costumes' => $costumes,
            'favorites' => $this->favorites->getForTrooper($trooper, true),
        ];

        return view('pages.account.favorite-costumes', $data);
    }
}
