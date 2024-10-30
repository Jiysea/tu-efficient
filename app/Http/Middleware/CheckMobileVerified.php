<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMobileVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('/'); // Redirect to login if not authenticated
        }

        // Check if the user is verified
        if (Auth::user()->isOngoingVerification()) {
            // Optionally, add additional logic here, such as logging or notifications
            return redirect()->route('verify.mobile');
        }

        return $next($request);
    }
}
