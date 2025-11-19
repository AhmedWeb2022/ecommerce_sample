<?php

namespace App\Modules\Product\Http\Resources\Dashboard\Cart;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {



        return [];
    }
}
