<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Troopers;

use App\Enums\MembershipRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Troopers\ProfileRequest;
use App\Models\Trooper;
use App\Services\BreadCrumbService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\ValidationException;

/**
 * Handles the display of the main trooper dashboard.
 *
 * This controller gathers various statistics for a trooper, such as troop counts by organization and costume, and displays them.
 */
class TrooperProfileSubmitHtmxController extends Controller
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
    public function __invoke(ProfileRequest $request, Trooper $trooper): Response|View
    {
        try
        {
            $validated = $request->validateInputs();

            $trooper->update($validated);

            $message = json_encode([
                'message' => 'Profile updated successfully!',
                'type' => 'success',
            ]);

            $data = [
                'trooper' => $trooper
            ];

            if ($trooper->wasChanged(Trooper::MEMBERSHIP_ROLE))
            {
                return response('ok')->header('HX-Refresh', 'true');
            }

            return response()
                ->view('pages.admin.troopers.profile', $data)
                ->header('X-Flash-Message', $message);
        }
        catch (ValidationException $e)
        {
            $errors = new ViewErrorBag();

            $errors->put('default', new MessageBag($e->errors()));

            view()->share('errors', $errors);

            $data = [
                'trooper' => $trooper
            ];

            $message = json_encode([
                'message' => 'Please fix the validation errors',
                'type' => 'danger',
            ]);

            return response()
                ->view('pages.admin.troopers.profile', $data)
                ->header('X-Flash-Message', $message);
        }
    }
}
