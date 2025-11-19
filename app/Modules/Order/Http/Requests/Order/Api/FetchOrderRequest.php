<?php

namespace App\Modules\Order\Http\Requests\Order\Api;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Order\Application\DTOS\Order\OrderFilterDTO;
use App\Modules\Order\Application\Enums\Order\OrderStatusTypeEnum;
use App\Modules\Order\Application\Enums\Order\OrderDurationTypeEnum;
use Illuminate\Validation\Rule;

class FetchOrderRequest extends BaseRequestAbstract
{
    protected $dtoClass = OrderFilterDTO::class;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function CustomRules(): array
    {
        // dd(OrderStatusTypeEnum::values());
        return [ // data validation
            'order_id' => 'nullable|integer|exists:orders,id',
            'order_number' => 'nullable|string',
            'status' => 'nullable|array',
            'status.*' => ['nullable', 'integer', Rule::enum(OrderStatusTypeEnum::class)], // Allow null or any valid status
            'duration_id' => 'nullable|integer|in:' . implode(',', OrderDurationTypeEnum::values()), // Assuming these are the valid duration IDs
        ];
    }
}
