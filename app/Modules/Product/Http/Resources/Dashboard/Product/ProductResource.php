<?php

namespace App\Modules\Product\Http\Resources\Dashboard\Product;

use App\Modules\Category\Http\Resources\Dashboard\Brand\BrandResource;
use App\Modules\Category\Http\Resources\Dashboard\Category\CategoryResource;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
