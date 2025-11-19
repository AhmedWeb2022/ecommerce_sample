<?php

namespace App\Modules\Order\Http\Resources\Api\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Order\Http\Resources\OrderItem\OrderItemResource;

class OrderDetailsResource extends OrderResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

       $orderData = parent::toArray($request);

       $orderData['items'] = OrderItemResource::collection($this->items);

       return $orderData;
    }
}
