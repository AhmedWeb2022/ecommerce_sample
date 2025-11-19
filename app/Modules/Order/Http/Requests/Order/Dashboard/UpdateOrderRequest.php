<?php

namespace App\Modules\Order\Http\Requests\Order\Dashboard;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\FileBase64OrFileRule;
use App\Modules\Order\Application\DTOS\Order\OrderDTO;
use App\Modules\Order\Application\Enums\Order\OrderStatusTypeEnum;
use Illuminate\Validation\Rule;

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
        return [ // data validation
            'order_id' => 'required|integer|exists:orders,id',
            'status' => ['nullable', 'integer', Rule::enum(OrderStatusTypeEnum::class)],
            // 'addresse_id' => 'required|integer|exists:user_addresses,id',
            // 'invoice_bill' => ['nullable', new FileBase64OrFileRule()],  'invoice_bill' => [
            'invoice_bill' => [
                Rule::requiredIf(fn () => $this->input('status') == OrderStatusTypeEnum::AWAITING_RECEIPT->value),
                new FileBase64OrFileRule()
            ],
            'reject_reason_id' => 'nullable|integer|exists:reject_reasons,id',
                'reject_reason_note' => 'nullable|string|max:255',
                'products' => 'nullable|array|min:1',
                'products.*.product_id' => 'required_with:products|integer|exists:products,id',
                'products.*.quantity' => 'required_with:products|integer|min:1',
            ];
    }
}
