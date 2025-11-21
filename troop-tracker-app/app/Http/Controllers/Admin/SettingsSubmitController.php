<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Handles the display of the main trooper dashboard.
 *
 * This controller gathers various statistics for a trooper, such as troop counts by organization and costume, and displays them.
 */
class SettingsSubmitController extends Controller
{
    /**
     * Handle the incoming request to display the dashboard page for a trooper.
     *
     * Fetches all relevant statistics for a given trooper (or the authenticated user)
     * and displays them on the main dashboard view. Redirects if the trooper is not found.
     *
     * @param Request $request The incoming HTTP request.
     * @return View|RedirectResponse The rendered dashboard page view or a redirect response.
     */
    public function __invoke(Request $request, Setting $setting): Response
    {
        $this->authorize('update', Setting::class);

        $value = $request->input('setting.' . $setting->key);

        $setting->value = $value;

        $setting->save();

        $message = json_encode([
            'message' => "Setting {$setting->key} successfully saved.",
            'type' => 'success',
        ]);

        return response('ok')->header('X-Flash-Message', $message);

    }
}
