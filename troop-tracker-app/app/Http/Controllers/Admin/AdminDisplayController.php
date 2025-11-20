<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trooper;
use App\Services\FlashMessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Handles the display of the main trooper dashboard.
 *
 * This controller gathers various statistics for a trooper, such as troop counts by organization and costume, and displays them.
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
        $not_approved = Trooper::pendingApprovals()->count();

        if ($not_approved == 1)
        {
            $msg = "There is {$not_approved} trooper ready for action!";
        }
        elseif ($not_approved > 1)
        {
            $msg = "There are {$not_approved} troopers ready for action!";
        }

        $this->flash->warning($msg);

        $data = ['not_approved' => $not_approved];

        return view('pages.admin.display', $data);
    }
}
