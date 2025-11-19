<?php

namespace App\Modules\Auth\Http\Resources\Customer;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this?->id ?? null,
            'name' => $this?->name ?? null,
            'email' => $this?->email ?? null,
            'phone' => $this?->phone ?? null,
            'image' => $this?->image_link ?? null,
            'token' => $this?->token ?? null
        ];
    }
}
