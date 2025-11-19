<?php

namespace App\Modules\Order\Http\Requests\Order\Dashboard;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Order\Application\DTOS\Order\OrderFilterDTO;
use App\Modules\Order\Application\Enums\Order\OrderStatusTypeEnum;

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
        return [ // data validation
            'order_number' => 'nullable|string',
            'order_id' => 'nullable|integer|exists:orders,id',
            'status' => 'nullable|in:' . implode(',', OrderStatusTypeEnum::values()),
            'user_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
