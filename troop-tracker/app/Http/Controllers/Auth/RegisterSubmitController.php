<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Contracts\AuthenticationInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\TrooperRepository;
use App\Services\FlashMessageService;
use Illuminate\Http\RedirectResponse;

/**
 * Handles the submission of the user registration form.
 */
class RegisterSubmitController extends Controller
{
    /**
     * @param AuthenticationInterface $auth The authentication service.
     * @param FlashMessageService $flash The flash message service.
     * @param TrooperRepository $troopers The trooper repository.
     */
    public function __construct(
        private readonly AuthenticationInterface $auth,
        private readonly FlashMessageService $flash,
        private readonly TrooperRepository $troopers
    ) {
    }

    /**
     * Handle the incoming registration request.
     *
     * @param RegisterRequest $request The validated registration form request.
     * @return RedirectResponse A redirect response back to the registration page with a status message or errors.
     */
    public function __invoke(RegisterRequest $request): RedirectResponse
    {
        $auth_user_id = $this->auth->verify(
            username: $request->username,
            password: $request->password
        );

        if ($auth_user_id == null)
        {
            return back()
                ->withInput()
                ->withErrors(['username' => 'Invalid Credentials']);
        }

        $this->troopers->register($request->validated(), $auth_user_id);

        $this->flash->success('Request submitted successfully! You will receive an e-mail when your request is approved or denied.');

        //  TODO FIX ROUTE TO SOMETHING THAT MAKES SENSE
        return redirect()->route('auth.register');
    }
}