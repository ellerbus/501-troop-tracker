<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\ClubRepository;
use App\Services\FlashMessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Displays the user registration page.
 */
class RegisterDisplayController extends Controller
{
    /**
     * @param FlashMessageService $flash The flash message service.
     * @param ClubRepository $clubs The club repository.
     */
    public function __construct(
        private readonly FlashMessageService $flash,
        private readonly ClubRepository $clubs
    ) {
    }

    /**
     * Handle the incoming request to display the registration form.
     *
     * @param Request $request The incoming HTTP request.
     * @return View The rendered registration page view.
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
