<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Base\Club;
use App\Models\Base\TrooperClub;
use App\Models\Base\TrooperSquad;
use App\Models\Squad;
use App\Repositories\ClubRepository;
use App\Repositories\TrooperRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles displaying the notification settings form via an HTMX request.
 */
class NotificationsDisplayHtmxController extends Controller
{
    /**
     * @param TrooperRepository $troopers The trooper repository.
     * @param ClubRepository $clubs The club repository.
     */
    public function __construct(
        private readonly TrooperRepository $troopers,
        private readonly ClubRepository $clubs)
    {
    }

    /**
     * Handle the incoming request to display the notification settings.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered notification settings view.
     */
    public function __invoke(Request $request): View
    {
        $data = $this->getTrooperNotifications();

        return view('pages.account.notifications', $data);
    }

    private function getTrooperNotifications(): array
    {
        $trooper = $this->troopers->getById(Auth::user()->id);

        $clubs = $this->clubs->findAllActive(include_squads: true);

        $trooper_clubs = $trooper->clubs()
            ->wherePivot(TrooperClub::NOTIFY, true)
            ->where(Club::ACTIVE, true)
            ->pluck('tt_clubs.' . Club::ID)
            ->toArray();

        $trooper_squads = $trooper->squads()
            ->wherePivot(TrooperSquad::NOTIFY, true)
            ->where(Squad::ACTIVE, true)
            ->pluck('tt_squads.' . Squad::ID)
            ->toArray();

        foreach ($clubs as $club)
        {
            $club->selected = in_array($club->id, $trooper_clubs);

            foreach ($club->squads as $squad)
            {
                $squad->selected = in_array($squad->id, $trooper_squads);
            }
        }

        $data = [
            'clubs' => $clubs,
            'instant_notification' => $trooper->instant_notification,
            'attendance_notification' => $trooper->attendance_notification,
            'command_staff_notification' => $trooper->command_staff_notification,
        ];

        return $data;
    }
}
