<?php

use App\Modules\Auth\Http\Controllers\Dashboard\ActivityLog\ActivityLogController;
use App\Modules\Auth\Http\Controllers\Dashboard\Customer\UserController;
use App\Modules\Auth\Http\Controllers\Dashboard\Employee\EmployeeController;
use App\Modules\Statistics\Http\Controllers\Dashboard\Permission\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Modules\Base\Http\Controllers\Base\BaseController;


Route::prefix('dashboard')->group(function () {
    Route::post('login', [EmployeeController::class, 'login']);
});

// Employee Routes

Route::prefix('dashboard')->middleware('baseAuthMiddleware:employee')->group(function () {
    Route::controller(EmployeeController::class)->group(function () {
        Route::post('logout', 'logout')->name('logout');
        Route::post('fetch_employees', 'fetchEmployees')->name('fetch_employees');
        Route::post('fetch_employee_details', 'fetchEmployeeDetails')->name('fetch_employee_details');
        Route::post('create_employee', 'createEmployee')->name('create_employee');
        Route::post('update_employee', 'updateEmployee')->name('update_employee');
        Route::post('delete_employee', 'deleteEmployee')->name('delete_employee');
    });
    Route::controller(UserController::class)->group(function () {
        Route::post('create_user', 'createUser');
        Route::post('delete_user', 'deleteUser');
        Route::post('fetch_users', 'fetchUsers');
        Route::post('fetch_user_details', 'fetchUserDetails');
        Route::post('update_user', 'updateUser');
    });
});
