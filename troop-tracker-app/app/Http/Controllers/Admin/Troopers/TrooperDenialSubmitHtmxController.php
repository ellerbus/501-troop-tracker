<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Troopers;

use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Models\Trooper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Handles the display of the main trooper dashboard.
 *
 * This controller gathers various statistics for a trooper, such as troop counts by organization and costume, and displays them.
 */
class TrooperDenialSubmitHtmxController extends Controller
{
    /**
     * Handle the incoming request to display the dashboard page for a trooper.
     *
     * Fetches all relevant statistics for a given trooper (or the authenticated user)
     * and displays them on the main dashboard view. Redirects if the trooper is not found.
     *
     * @param Request $request The incoming HTTP request.
     */
    public function __invoke(Request $request, Trooper $trooper): Response|View
    {
        $this->authorize('approve', $trooper);

        $data = [
            'trooper' => $trooper
        ];

        $trooper->membership_status = MembershipStatus::Denied;

        $trooper->save();

        $message = json_encode([
            'message' => "Trooper {$trooper->name} denied",
            'type' => 'danger',
        ]);

        return response()
            ->view('pages.admin.troopers.approval', $data)
            ->header('X-Flash-Message', $message);
    }
}
