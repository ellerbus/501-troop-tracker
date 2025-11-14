<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Contracts\ForumInterface;
use App\Http\Controllers\Controller;
use App\Models\Trooper;
use App\Services\BreadCrumbService;
use App\Services\FlashMessageService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $trooper = Trooper::findOrFail($trooper_id);

        if ($trooper_id == Auth::user()->id)
        {
            $this->crumbs->addRoute('Account', 'account.display');
            $this->crumbs->add('Dashboard');
        }

        $year_ago = Carbon::now()->subYear();

        $data = [
            'trooper' => $trooper,
            // 'trooper_rank' => $this->stats->getTrooperRanking($trooper->id),
            // 'image_url' => $this->forum->getAvatarUrl($trooper->user_id),
            // 'total_troops' => $this->stats->getTroopCountSince($trooper->id, null),
            // 'total_troops_since' => $this->stats->getTroopCountSince($trooper->id, $year_ago),
            // 'total_troops_club' => $this->stats->getTroopCountsByClub($trooper->id),
            // 'favorite_costume' => $this->stats->getFavoriteCostumeForTrooper($trooper->id),
            // 'volunteer_hours' => $this->stats->getTotalCharityHoursForTrooper($trooper->id),
        ];

        // $funds = $this->stats->getTotalCharityFundsForTrooper($trooper->id);

        // $data['direct_funds'] = $funds['direct'];
        // $data['indirect_funds'] = $funds['indirect'];

        return view('pages.dashboard.display', $data);
    }
}
