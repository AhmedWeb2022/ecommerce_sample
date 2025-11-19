<?php


use Illuminate\Support\Facades\Route;
use App\Modules\Order\Http\Controllers\Order\Dashboard\OrderController;

Route::prefix('dashboard')->middleware('baseAuthMiddleware:employee')->group(function () {

    Route::controller(OrderController::class)->group(function () {
        Route::post('fetch_orders', 'fetchOrders');
        Route::post('fetch_order_details', 'fetchOrderDetails');
        Route::post('delete_order', 'deleteOrder');
    });
});
