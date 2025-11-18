<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Contracts\ForumInterface;
use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\EventTrooper;
use App\Models\Trooper;
use App\Services\BreadCrumbService;
use App\Services\FlashMessageService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Displays the main dashboard management page.
 */
class DashboardDisplayController extends Controller
{
    /**
     * Creates a new DashboardDisplayController instance.
     *
     * @param FlashMessageService $flash The flash message service.
     * @param BreadCrumbService $crumbs The breadcrumb service.
     * @param ForumInterface $forum The forum integration service.
     */
    public function __construct(
        private readonly FlashMessageService $flash,
        private readonly BreadCrumbService $crumbs,
        private readonly ForumInterface $forum
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

        $trooper = Trooper::with('trooper_achievement')->findOrFail($trooper_id);

        if ($trooper_id == Auth::user()->id)
        {
            $this->crumbs->addRoute('Account', 'account.display');
            $this->crumbs->add('Dashboard');
        }

        $year_ago = Carbon::now()->subYear();

        $data = [
            'trooper' => $trooper,
            'total_troops_by_club' => $this->getTroopsByClub($trooper),
            'total_troops_by_costume' => EventTrooper::costumeCountByTrooper($trooper->id),
        ];

        return view('pages.dashboard.display', $data);
    }

    private function getTroopsByClub(Trooper $trooper): Collection
    {
        $clubs = Club::active()->get();

        $troops_by_club = EventTrooper::clubCountByTrooper($trooper->id);

        foreach ($clubs as $club)
        {
            if (isset($troops_by_club[$club->id]))
            {
                $club->troop_count = $troops_by_club[$club->id];
            }
        }

        return $clubs;

    }
}
