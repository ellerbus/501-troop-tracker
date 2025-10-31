<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ClubService;
use App\Services\FlashMessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RegisterDisplayController extends Controller
{
    public function __construct(
        private readonly FlashMessageService $flash,
        private readonly ClubService $clubs
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $clubs = $this->clubs->findAllActive(include_squads: true);

        $data = [
            'clubs' => $clubs
        ];

        return view('pages.auth.register', $data);
    }
}
