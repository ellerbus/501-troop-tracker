<?php

declare(strict_types=1);

namespace App\Http\Controllers\AUth;

use App\Models\Club;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Handles HTMX requests for updating club selections on the registration page.
 */
class RegisterHtmxController
{
    /**
     */
    public function __construct()
    {
    }

    /**
     * Handle the incoming HTMX request to render a club selection partial.
     *
     * @param Request $request The incoming HTTP request.
     * @param Club $club The club model instance from route model binding.
     * @return View The rendered club selection partial view.
     */
    public function __invoke(Request $request, Club $club): View
    {
        $club->selected = $request->input("clubs.{$club->id}.selected") === '1';

        $data = ['club' => $club];

        return view('pages.auth.club-selection', $data);
    }
}
