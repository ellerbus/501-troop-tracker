<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Repositories\ClubRepository;
use App\Repositories\TrooperRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Handles the submission of the notification settings form via an HTMX request.
 */
class NotificationsSubmitHtmxController extends Controller
{
    /**
     * @param TrooperRepository $troopers The trooper repository.
     */
    public function __construct(
        private readonly ClubRepository $clubs,
        private readonly TrooperRepository $troopers)
    {
    }

    /**
     * Handle the incoming request to update notification settings.
     *
     * @param Request $request The incoming HTTP request.
     * @return Response|View The rendered notification settings view with a flash message header.
     */
    public function __invoke(Request $request): Response|View
    {
        $data = $this->updateTrooperNotifications($request->all());

        $message = json_encode([
            'message' => 'Notifications updated successfully!',
            'type' => 'success',
        ]);

        return response()
            ->view('pages.account.notifications', $data)
            ->header('X-Flash-Message', $message);
    }

    private function updateTrooperNotifications(array $data): array
    {
        $clubs = $this->clubs->findAllActive(include_squads: true);

        $trooper = $this->troopers->getById(Auth::user()->id);

        $updates = [
            'efast' => $data['instant_notification'] ?? 0,
            'econfirm' => $data['attendance_notification'] ?? 0,
            'ecommandnotify' => $data['command_staff_notification'] ?? 0,
            'esquad0' => 0,
            'esquad1' => 0,
            'esquad2' => 0,
            'esquad3' => 0,
            'esquad4' => 0,
            'esquad5' => 0,
            'esquad6' => 0,
            'esquad7' => 0,
            'esquad8' => 0,
            'esquad9' => 0,
            'esquad10' => 0,
            'esquad13' => 0,
        ];

        foreach ($clubs as $club)
        {
            $notify = isset($data['clubs'][$club->id]['notification']);

            $updates[$club->troopers_notification_field] = $notify;

            $club->selected = $notify;

            $trooper->trooperClubs()
                ->where('club_id', $club->id)
                ->update(['notify' => $notify]);

            foreach ($club->squads as $squad)
            {
                $notify = isset($data['squads'][$squad->id]['notification']);

                $updates[$squad->troopers_notification_field] = $notify;

                $squad->selected = $notify;

                $trooper->trooperSquads()
                    ->where('squad_id', $squad->id)
                    ->update(['notify' => $notify]);
            }
        }

        $trooper->update($updates);

        return [
            'clubs' => $clubs,
            'instant_notification' => $updates['efast'],
            'attendance_notification' => $updates['econfirm'],
            'command_staff_notification' => $updates['ecommandnotify']
        ];
    }
}