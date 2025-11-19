<?php

namespace App\Modules\Product\Http\Requests\Api\Cart;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Product\Application\DTOS\Cart\CartFilterDTO;

class FetchCartRequest extends BaseRequestAbstract
{
    protected  $dtoClass = CartFilterDTO::class;
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
        ];
    }
}
