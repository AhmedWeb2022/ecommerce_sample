<?php

namespace App\Modules\Product\Http\Resources\Api\Cart;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Modules\Category\Http\Resources\Api\Category\CategoryResource;

class CartResource extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
        // $this->product_entity = $this->product->getEntity();
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'product_id'            => $this->product_id,
            'title'                  => $this->product->title,
            'product_image'         => $this->product->image_link,
            'quantity'              => $this->quantity,
            'category'              => new CategoryResource($this->product->category),
        ];
    }
}
