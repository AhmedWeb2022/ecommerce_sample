<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Order\Http\Controllers\Order\Api\OrderController;

Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['baseAuthMiddleware:user']], function () {

        Route::controller(OrderController::class)->group(function () {
            Route::post('create_order', 'createOrder');
            Route::post('fetch_orders', 'fetchOrders');
            Route::post('fetch_order_details', 'fetchOrderDetails');
            Route::post('delete_order', 'deleteOrder');
        });
    });
});
