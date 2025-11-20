<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Trooper;
use App\Models\TrooperOrganization;
use App\Models\TrooperUnit;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles displaying the notification settings form via an HTMX request.
 */
class NotificationsDisplayHtmxController extends Controller
{
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
        $trooper = Trooper::findOrFail(Auth::user()->id);

        $organizations = Organization::active(include_squads: true)->get();

        $trooper_clubs = $trooper->organizations()
            ->wherePivot(TrooperOrganization::NOTIFY, true)
            ->where(Organization::ACTIVE, true)
            ->pluck('tt_clubs.' . Organization::ID)
            ->toArray();

        $trooper_squads = $trooper->units()
            ->wherePivot(TrooperUnit::NOTIFY, true)
            ->where(Unit::ACTIVE, true)
            ->pluck('tt_squads.' . Unit::ID)
            ->toArray();

        foreach ($organizations as $organization)
        {
            $organization->selected = in_array($organization->id, $trooper_clubs);

            foreach ($organization->units as $unit)
            {
                $unit->selected = in_array($unit->id, $trooper_squads);
            }
        }

        $data = [
            'organizations' => $organizations,
            'instant_notification' => $trooper->instant_notification,
            'attendance_notification' => $trooper->attendance_notification,
            'command_staff_notification' => $trooper->command_staff_notification,
        ];

        return $data;
    }
}
