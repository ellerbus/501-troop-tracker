<?php

declare(strict_types=1);

namespace App\Http\Controllers\AUth;

use App\Models\Club;
use App\Services\ClubService;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Illuminate\Contracts\View\View;

class RegisterHtmxController
{
    public function __construct(private readonly ClubService $clubs)
    {
    }

    public function __invoke(HtmxRequest $request, Club $club): View
    {
        $club->selected = $request->input("clubs.{$club->id}.selected") === '1';

        $data = ['club' => $club];

        return view('pages.auth.partials.club-selection', $data);
    }
}
