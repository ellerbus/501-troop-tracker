<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
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
        //  TODO LEGACY CLEAN UP
        $trooper = $this->troopers->getById(Auth::user()->id);

        $clubs = $this->clubs->findAllActive(include_squads: true);

        foreach ($clubs as $club)
        {
            $notify_field = $club->troopers_notification_field;

            $notify = isset($trooper->{$notify_field}) && $trooper->{$notify_field};

            $trooper_club_notify = $trooper->trooperClubs()
                ->where('club_id', $club->id)
                ->first();

            $club->selected = $notify || (isset($trooper_club_notify) && $trooper_club_notify);

            foreach ($club->squads as $squad)
            {
                $notify_field = $squad->troopers_notification_field;

                $notify = isset($trooper->{$notify_field}) && $trooper->{$notify_field};

                $trooper_squad_notify = $trooper->trooperSquads()
                    ->where('squad_id', $squad->id)
                    ->pluck('notify')
                    ->first();

                $squad->selected = $notify || (isset($trooper_squad_notify) && $trooper_squad_notify);
            }
        }

        $data = [
            'clubs' => $clubs,
            'instant_notification' => $trooper->efast,
            'attendance_notification' => $trooper->econfirm,
            'command_staff_notification' => $trooper->ecommandnotify,
        ];

        return $data;
    }
}
