<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Trooper;
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
        $clubs = Club::active(include_squads: true)->get();

        $trooper = Trooper::findOrFail(Auth::user()->id);

        $data['instant_notification'] = $data['instant_notification'] ?? false;
        $data['attendance_notification'] = $data['attendance_notification'] ?? false;
        $data['command_staff_notification'] = $data['command_staff_notification'] ?? false;

        $trooper->instant_notification = $data['instant_notification'];
        $trooper->attendance_notification = $data['attendance_notification'];
        $trooper->command_staff_notification = $data['command_staff_notification'];

        $trooper->save();

        foreach ($clubs as $club)
        {
            $notify = isset($data['clubs'][$club->id]['notification']);

            $club->selected = $notify;

            $trooper->clubs()->updateExistingPivot($club->id, [
                'notify' => $notify,
            ]);

            foreach ($club->squads as $squad)
            {
                $notify = isset($data['squads'][$squad->id]['notification']);

                $squad->selected = $notify;

                $trooper->squads()->updateExistingPivot($squad->id, [
                    'notify' => $notify,
                ]);
            }
        }

        $data['clubs'] = $clubs;

        return $data;
    }
}