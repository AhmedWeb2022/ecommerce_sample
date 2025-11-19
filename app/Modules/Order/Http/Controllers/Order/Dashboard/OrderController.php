<?php

namespace App\Modules\Order\Http\Controllers\Order\Dashboard;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Order\Application\UseCases\Order\OrderUseCase;
use App\Modules\Order\Http\Requests\Order\Dashboard\OrderIdRequest;
use App\Modules\Order\Http\Requests\Order\Dashboard\FetchOrderRequest;
use App\Modules\Order\Http\Requests\Order\Dashboard\CreateOrderRequest;
use App\Modules\Order\Http\Requests\Order\Dashboard\UpdateOrderRequest;

class OrderController extends Controller
{
    protected $orderUseCase;

    public function __construct(OrderUseCase $orderUseCase)
    {
        $this->orderUseCase = $orderUseCase;
    }


    public function fetchOrders(FetchOrderRequest $request)
    {
        return $this->orderUseCase->fetchOrders($request->toDTO())->response();
    }

    public function fetchOrderDetails(OrderIdRequest $request)
    {
        return $this->orderUseCase->fetchOrderDetails($request->toDTO())->response();
    }

    public function deleteOrder(OrderIdRequest $request)
    {
        return $this->orderUseCase->deleteOrder($request->toDTO())->response();
    }
}
