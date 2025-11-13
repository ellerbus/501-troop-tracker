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
 * Handles the deletion of a favorite costume via an HTMX request.
 */
class FavoriteCostumesDeleteHtmxController extends Controller
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
     * Handle the incoming request to delete a favorite costume.
     *
     * This method removes a costume from the user's favorites list based on the provided 'costume_id'.
     * It then returns a view partial containing the updated list of favorite costumes.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered favorite costumes view, intended for an HTMX response.
     */
    public function __invoke(Request $request): View
    {
        $trooper = $this->troopers->getById(Auth::user()->id);

        $costume_id = (int) $request->get('costume_id', -1);

        if ($costume_id > -1)
        {
            $this->favorites->remove($trooper->id, $costume_id);
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
