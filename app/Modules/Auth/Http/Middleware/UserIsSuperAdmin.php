<?php
namespace App\Modules\Auth\Http\Middleware;

use App\Modules\Auth\Http\Enums\EmployeeTypeEnum;
use App\Modules\Auth\Infrastructure\Persistence\Models\Admin\Employee;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Modules\Base\Domain\Enums\AuthTypeEnum;

class UserIsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user || $user->employee_type !== EmployeeTypeEnum::SUPER_ADMIN->value) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access. Super Admins only.',
            ], 403);
        }

        return $next($request);
    }
}
