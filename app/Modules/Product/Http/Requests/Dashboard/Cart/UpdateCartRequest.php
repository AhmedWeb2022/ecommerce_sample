<?php

namespace App\Modules\Product\Http\Requests\Dashboard\Cart;

use App\Modules\Product\Application\DTOS\Cart\CartDTO;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class UpdateCartRequest extends BaseRequestAbstract
{
    protected  $dtoClass = CartDTO::class;
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
    public function customRules(): array
    {
        return [ // data validation
            'cart_id' => 'required|integer|exists:carts,id',
            'product_id' => 'nullable|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ];
    }
}
