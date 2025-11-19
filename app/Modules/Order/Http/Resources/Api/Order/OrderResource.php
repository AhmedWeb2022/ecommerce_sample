<?php

namespace App\Modules\Order\Http\Resources\Api\Order;

use App\Modules\Auth\Http\Resources\Customer\UserAddressResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'user_id' => $this->user_id ?? 0,
            'order_number' => $this->order_number,
            'reciept' => $this->image_link,
            'invoice_bill' => $this->invoice_link,
            'address' => $this->address ? new UserAddressResource($this->address) : null,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'delivery_date' => $this->delivery_date,
            'status' => $this->status,
            'total_price' => $this->total_price ?? 0,
            "total_after_discount" => $this->total_after_discount ?? 0,
            "total_after_tax" => $this->total_after_tax ?? 0,
            'tax_amount' => $this->tax_amount ?? 0,
            'delivery_address' => $this->address->address ?? '',
            'reject_reason' => $this->reject_reason ?? '',
            'phone' => $this->phone,
            'order_date' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
