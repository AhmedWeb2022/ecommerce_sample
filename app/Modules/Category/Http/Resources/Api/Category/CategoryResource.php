<?php

namespace App\Modules\Category\Http\Resources\Api\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'title' => $this->title ?? '',
            'subtitle' => $this->subtitle ?? '',
            'is_active' => boolval($this->is_active),
            'image' => $this->image_link,
            'children' => CategoryResource::collection($this->children),
        ];
    }
}
