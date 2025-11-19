<?php

namespace App\Modules\Product\Http\Resources\Dashboard\ProductOffer;

use App\Modules\Product\Http\Resources\Dashboard\Product\GeneralProductResource;
use App\Modules\Product\Http\Resources\Dashboard\Product\ProductResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ProductOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $title = $request->header('Accept-Language') !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this->product) : getTranslationAndLocale($this->product->translations, 'title');
        return [
            'id' => $this->id,
            'titles' => $title ,
            'product_id' => $this->product_id,
            'is_active' => $this->is_active,
            'offer_price' => doubleval($this->offer_price),
            'from_date' => $this->from_date ? Carbon::parse($this->from_date)->format('Y-m-d') : "",
            'to_date' => $this->to_date ? Carbon::parse($this->to_date)->format('Y-m-d') : "",
            // 'product' => new ProductResource($this->product),
            'category_id' => $this->product->category_id,
            'original_Price' => $this->product->purchase_price,
            'product' => $this->product ?  new GeneralProductResource($this->product) : (object)[],
        ];
    }

}
