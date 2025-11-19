<?php

namespace App\Modules\Order\Http\Resources\OrderItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Category\Http\Resources\Api\Category\CategoryResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this->product) : getTranslationAndLocale($this->product?->translations, 'title');
        $purchase_price_or_offer_price = $this->product->has_offer ? $this->product->active_offer?->offer_price ?? 0 : $this->product->purchase_price ?? 0;
        return [
            'id'                    => $this->id,
            'product_id'            => $this->product_id,
            'title'                  => $title,
            'product_image'         => $this->product->image_link,
            'quantity'              => $this->quantity,
            'price_after_discount'  => floatval($purchase_price_or_offer_price),
            'price'                 => floatval($this->product->purchase_price ?? 0),
            'category'              => new CategoryResource($this->product->category),
        ];
    }
}
