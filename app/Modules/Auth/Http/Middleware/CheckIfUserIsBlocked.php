<?php

namespace App\Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserIsBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // same as auth()->user()

        if ($user && $user->is_blocked) {
            // Revoke current Sanctum token
            $user->currentAccessToken()?->delete();

            return response()->json([
                'message' => 'Your account has been blocked.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
