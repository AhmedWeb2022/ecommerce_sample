<?php

namespace App\Modules\Product\Http\Resources\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Category\Http\Resources\Api\Category\CategoryResource;

class ProductResource extends JsonResource
{
    public $has_offer;
    public $active_offer;
    public $product_entity;
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->has_offer = $this->getEntity()->hasOffer();
        $this->active_offer = $this->getEntity()->getActiveOffer();
        // $this->product_entity = $this->getEntity();
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'slug' => $this->slug,
            'image' => $this->imageLink,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category' => new CategoryResource($this->category),
        ];
    }
}
