<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Notices;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Services\BreadCrumbService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ListController
 *
 * Handles the display of the main notices list in the admin section.
 * This controller fetches and displays a list of notices with their assignment
 * status for the authenticated user.
 * @package App\Http\Controllers\Admin\Notices
 */
class ListController extends Controller
{
    /**
     * ListController constructor.
     *
     * @param BreadCrumbService $crumbs The service for managing breadcrumbs.
     */
    public function __construct(private readonly BreadCrumbService $crumbs)
    {
    }

    /**
     * Handle the request to display the notices list page.
     *
     * Sets up breadcrumbs, retrieves notices with assignment data for the
     * authenticated user, and returns the list view.
     *
     * @param Request $request The incoming HTTP request object.
     * @return View The rendered notices list view.
     */
    public function __invoke(Request $request): View
    {
        $this->crumbs->addRoute('Command Staff', 'admin.display');
        $this->crumbs->add('Notices');

        $notices = Notice::active()->moderatedBy(Auth::user())->get();

        $data = [
            'notices' => $notices
        ];

        return view('pages.admin.notices.list', $data);
    }
}
