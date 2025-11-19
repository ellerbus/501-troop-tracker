<?php

namespace App\Http\Middleware;

use App\Enums\TrooperPermissions;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use ValueError;

class CheckTrooperPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (count($roles) > 0)
        {
            $user = Auth::user();

            if (!$user)
            {
                abort(401, 'Unauthorized.');
            }

            foreach ($roles as $role)
            {
                if (is_string($role))
                {
                    try
                    {
                        $role = TrooperPermissions::from($role);
                    }
                    catch (ValueError $e)
                    {
                        throw new InvalidArgumentException("Invalid permission role: '{$role}'");
                    }
                }

                if ($user->permissions === $role)
                {
                    return $next($request);
                }
            }

            //  don't match any role - now - do we?!
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }

}
