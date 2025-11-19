<?php

namespace App\Modules\Auth\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // dd($this->image),
            'id' => $this?->id ?? null,
            'name' => $this?->name ?? null,
            'email' => $this?->email ?? null,
            'phone' => $this?->phone ?? null,
            'image' => $this?->image_link ?? null,
            'token' => $this?->token ?? null

        ];
    }
}
