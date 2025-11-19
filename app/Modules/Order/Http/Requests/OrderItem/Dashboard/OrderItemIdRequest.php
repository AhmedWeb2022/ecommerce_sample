<?php

namespace App\Modules\Order\Http\Requests\OrderItem\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Order\Application\DTOS\OrderItem\OrderItemDTO;
use Illuminate\Validation\Rule;

class OrderItemIdRequest extends BaseRequestAbstract
{
    protected $dtoClass = OrderItemDTO::class;
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
            'order_item_id' => [
                'required',
                'integer',
                Rule::exists('order_items', 'id'),
            ]
        ];
    }
}
