<?php

namespace App\Modules\Order\Http\Controllers\Order\Api;


use App\Http\Controllers\Controller;
use App\Modules\Category\Application\Enums\View\ViewTypeEnum;
use App\Modules\Order\Http\Requests\Order\Api\OrderIdRequest;
use App\Modules\Order\Application\UseCases\Order\OrderUseCase;
use App\Modules\Order\Http\Requests\Order\Api\FetchOrderRequest;
use App\Modules\Order\Http\Requests\Order\Api\CreateOrderRequest;
use App\Modules\Order\Http\Requests\Order\Api\UpdateOrderRequest;
use App\Modules\Order\Http\Requests\Order\Api\FetchOrderDetailsRequest;

class OrderController extends Controller
{
    protected $orderUseCase;

    public function __construct(OrderUseCase $orderUseCase)
    {
        $this->orderUseCase = $orderUseCase;
    }



    public function fetchOrders(FetchOrderRequest $request)
    {
        return $this->orderUseCase->fetchOrders($request->toDTO(), ViewTypeEnum::MOBILE->value)->response();
    }

    public function fetchOrderDetails(FetchOrderDetailsRequest $request)
    {
        return $this->orderUseCase->fetchOrderDetails($request->toDTO(), ViewTypeEnum::MOBILE->value)->response();
    }


    public function createOrder(CreateOrderRequest $request)
    {
        // dd($request->validated());
        return $this->orderUseCase->createOrder($request->toDTO())->response();
    }

    public function deleteOrder(OrderIdRequest $request)
    {
        return $this->orderUseCase->deleteOrder($request->toDTO())->response();
    }
}
