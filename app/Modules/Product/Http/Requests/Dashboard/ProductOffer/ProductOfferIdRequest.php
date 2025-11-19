<?php

namespace App\Modules\Product\Http\Requests\Dashboard\ProductOffer;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Product\Application\DTOS\ProductOffer\ProductOfferFilterDTO;
use Illuminate\Validation\Rule;

class ProductOfferIdRequest extends BaseRequestAbstract
{
    protected $dtoClass = ProductOfferFilterDTO::class;
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
        return [
            'product_offer_id' => [
                'required',
                'integer',
                Rule::exists('product_offers', 'id'),
            ],

        ];
    }
}
