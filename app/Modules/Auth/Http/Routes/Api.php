<?php

use App\Modules\Auth\Http\Controllers\Api\Admin\EmployeeController;
use App\Modules\Auth\Http\Controllers\Api\Customer\AuthUserController;
use App\Modules\Auth\Http\Controllers\Api\Customer\UserAddressController;
use App\Modules\Auth\Http\Controllers\Api\Customer\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('api')->group(function () {

    // Public routes
    Route::controller(UserController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('check_phone', 'checkCredential');
        Route::post('check_code', 'checkCode');
        Route::post('reset_password', 'resetPassword');
        Route::post('get_users_device_tokens', 'getUsersDeviceTokens');
        Route::get('get_all_user_ids', 'getAllUserIds');
    });

    // Protected routes (auth required)
    Route::middleware('baseAuthMiddleware:user')->group(function () {

        // Logout route
        Route::controller(AuthUserController::class)->group(function () {
            Route::post('logout', 'logout');
        });

        // Block check middleware group
        Route::middleware('checkIfUserIsBlocked')->group(function () {
            Route::controller(AuthUserController::class)->group(function () {
                Route::post('change_password', 'changePassword');
                Route::post('update_profile', 'updateAccount');
                Route::post('change_allow_notifications', 'updateAccount');
                Route::post('complete_data', 'updateAccount');
                Route::post('change_phone', 'changePhone');
                Route::post('delete_account', 'deleteAccount');
                Route::post('check_approved', 'checkApproved');
                Route::post('delete_another_address', 'deleteAnotherAddress');
            });
            Route::post('create_or_update_national_address', [UserAddressController::class, 'createOrUpdateNationalAddress']);
            Route::post('update_or_create_another_address', [UserAddressController::class, 'updateOrCreateAnotherAddress']);
        });
    });

});






