<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Services\BreadCrumbService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class OrganizationsDisplayController
 *
 * Handles the display of the main organizations list in the admin section.
 * This controller fetches and displays a list of organizations, filtering the results
 * based on the authenticated user's role. Administrators see all organizations, while
 * other roles see only the organizations they are assigned to moderate.
 * @package App\Http\Controllers\Admin\Organizations
 */
class ListController extends Controller
{
    /**
     * OrganizationsDisplayController constructor.
     *
     * @param BreadCrumbService $crumbs The breadcrumb service for managing navigation history.
     */
    public function __construct(private readonly BreadCrumbService $crumbs)
    {
    }

    /**
     * Handle the incoming request to display the organizations list page.
     *
     * Sets up breadcrumbs, retrieves the appropriate list of organizations based on the
     * user's role, and returns the main organizations display view.
     *
     * @param Request $request The incoming HTTP request.
     * @return View|RedirectResponse The rendered dashboard page view or a redirect response.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        $this->crumbs->addRoute('Command Staff', 'admin.display');
        $this->crumbs->add('Organizations');

        $organizations = Organization::withAllAssignments(Auth::user()->id)->get();

        $data = [
            'organizations' => $organizations
        ];

        return view('pages.admin.organizations.list', $data);
    }
}
