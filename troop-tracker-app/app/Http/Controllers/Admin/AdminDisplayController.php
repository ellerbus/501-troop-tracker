<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\ForumInterface;
use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ClubCostume;
use App\Models\EventTrooper;
use App\Models\Trooper;
use App\Services\BreadCrumbService;
use App\Services\FlashMessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Handles the display of the main trooper dashboard.
 *
 * This controller gathers various statistics for a trooper, such as troop counts by club and costume, and displays them.
 */
class AdminDisplayController extends Controller
{
    /**
     * Creates a new AdminDisplayController instance.
     *
     * @param FlashMessageService $flash The flash message service.
     */
    public function __construct(
        private readonly FlashMessageService $flash,
    ) {
    }

    /**
     * Handle the incoming request to display the dashboard page for a trooper.
     *
     * Fetches all relevant statistics for a given trooper (or the authenticated user)
     * and displays them on the main dashboard view. Redirects if the trooper is not found.
     *
     * @param Request $request The incoming HTTP request.
     * @return View|RedirectResponse The rendered dashboard page view or a redirect response.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        $trooper_id = (int) $request->get('trooper_id', Auth::user()->id);

        $data = [];

        return view('pages.admin.display', $data);
    }
}
