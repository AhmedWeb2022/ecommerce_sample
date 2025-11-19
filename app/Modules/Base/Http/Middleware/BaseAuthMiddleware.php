<?php

namespace App\Modules\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Support\AuthenticatesViaToken;
use App\Modules\Base\Domain\Support\AuthGuardServiceResolver;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\UserApiService;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\EmployeeApiService;
use Illuminate\Support\Facades\Log;

class BaseAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // Define valid guards explicitly, you can add more as needed
        $validGuards = array_map(fn($guard) => $guard->value, AuthGurdTypeEnum::cases());
        // dd($guards);
        // Ensure at least one guard is passed and is valid
        if (empty($guards)) {
            return response()->json(['error' => 'No guard specified'], 400);
        }

        // Filter out invalid guards that are not in the valid guards list
        $guards = array_filter($guards, fn($g) => in_array($g, $validGuards));

        if (empty($guards)) {
            return response()->json(['error' => 'Invalid guard specified'], 400);
        }
        Log::info("Guards: " . json_encode($guards));
        // Loop through the guards and check authentication
        foreach ($guards as $guard) {
            // dd($guard);
            // If guard is NOT configured in `auth.php`
            if (!array_key_exists($guard, config('auth.guards'))) {
                // dd($guard);
                $token = $request->bearerToken();

                if (!$token) {
                    throw new AuthenticationException('Unauthenticated.', $guards);
                }

                $authService = AuthGuardServiceResolver::resolve($guard);
                // dd($authService);
                if (!$authService instanceof AuthenticatesViaToken) {
                    return response()->json(['error' => 'Invalid guard specified'], 400);
                }

                try {
                    $authResult = $authService->checkAuth($token);
                    // dd($authResult);
                    if (!isset($authResult['status']) || !$authResult['status']) {
                        throw new AuthenticationException('Unauthenticated.', $guards);
                    }

                    return $next($request);
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 401);
                }
            }

            // Guard is configured, use Laravel's default auth
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard);
                return $next($request);
            }
        }

        // If not authenticated with any valid guard, throw an exception
        throw new AuthenticationException('Unauthenticated.', $guards);
    }
}
