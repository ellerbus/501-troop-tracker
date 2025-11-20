<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Troopers;

use App\Http\Controllers\Controller;
use App\Services\BreadCrumbService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
//dd(array_filter(get_included_files(), fn($f) => str_contains($f, 'Trooper')));

/**
 * Handles the display of the main trooper dashboard.
 *
 * This controller gathers various statistics for a trooper, such as troop counts by organization and costume, and displays them.
 */
class TrooperApprovalDisplayController extends Controller
{
    public function __construct(private readonly BreadCrumbService $crumbs)
    {

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
        $this->crumbs->addRoute('Command Staff', 'admin.display');
        // $this->crumbs->addRoute('Troopers', 'admin.troopers.display');
        $this->crumbs->add('Approvals');

        $with = [
            'organizations'
            // => function (Builder $q)
            // {
            //     return $q->orderBy(Organization::NAME);
            // },
        ];

        //        $troopers = Trooper::with($with)->pendingApprovals()->get();

        $data = [
            'troopers' => []
        ];

        return view('pages.admin.troopers.approvals', $data);
    }
}
