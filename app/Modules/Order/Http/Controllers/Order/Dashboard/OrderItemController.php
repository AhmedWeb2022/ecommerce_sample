<?php

namespace App\Modules\Order\Http\Controllers\Order\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Order\Application\UseCases\Order\OrderItemUseCase;
use App\Modules\Order\Http\Requests\OrderItem\Dashboard\OrderItemIdRequest;

class OrderItemController extends Controller
{
    protected $orderItemUseCase;

    public function __construct(OrderItemUseCase $orderItemUseCase)
    {
        $this->orderItemUseCase = $orderItemUseCase;
    }


    public function deleteOrderItem(OrderItemIdRequest $request)
    {
        return $this->orderItemUseCase->deleteOrderItem($request->toDTO())->response();
    }
}
