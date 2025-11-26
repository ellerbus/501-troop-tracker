<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Organizations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Organizations\UpdateRequest;
use App\Models\Organization;
use App\Services\FlashMessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class OrganizationsDisplayController
 *
 * Handles the display of the main organizations list in the admin section.
 * This controller fetches and displays a list of organizations, filtering the results
 * based on the authenticated user's role. Administrators see all organizations, while
 * other roles see only the organizations they are assigned to moderate.
 * @package App\Http\Controllers\Admin\Organizations
 */
class UpdateSubmitController extends Controller
{
    public function __construct(private readonly FlashMessageService $flash)
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
    public function __invoke(UpdateRequest $request, Organization $organization): RedirectResponse
    {
        $organization->name = $request->name;

        $organization->save();

        $this->flash->success('Organization Updated Succesfully!');

        return redirect()->route('admin.organizations.list');
    }
}
