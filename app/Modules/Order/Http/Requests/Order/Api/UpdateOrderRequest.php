<?php

namespace App\Modules\Order\Http\Requests\Order\Api;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Order\Application\DTOS\Order\OrderDTO;

class UpdateOrderRequest extends BaseRequestAbstract
{
    protected $dtoClass = OrderDTO::class;
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
        return [
            'order_id'     => 'required|integer|exists:orders,id',
            'addresse_id'  => 'required|integer|exists:user_addresses,id',
            'reciept'      => 'nullable',
        ];
    }
}
