<?php

namespace App\Modules\Product\Http\Requests\Dashboard\Cart;

use App\Modules\Product\Application\DTOS\Cart\CartDTO;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class CreateCartRequest extends BaseRequestAbstract
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
            "user_id" => "required|integer|exists:users,id",
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}
