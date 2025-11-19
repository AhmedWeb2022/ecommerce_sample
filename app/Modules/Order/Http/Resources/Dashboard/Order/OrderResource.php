<?php

namespace App\Modules\Order\Http\Resources\Dashboard\Order;

use App\Modules\Auth\Http\Resources\Customer\UserAddressResource;
use App\Modules\Auth\Http\Resources\Dashboard\Customer\OrderUserResource;
use App\Modules\Order\Application\Enums\Order\OrderStatusTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Order\Http\Resources\Hashtag\HashtagResource;
use App\Modules\Order\Http\Resources\Category\CategoryResource;
use App\Modules\Order\Infrastructure\Persistence\Models\Category\Category;

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
            'user_id' => $this->user_id ?? 0, // object of UserAddressResource
            'date' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : '',
            'status' => $this->status,
        ];
    }
}
