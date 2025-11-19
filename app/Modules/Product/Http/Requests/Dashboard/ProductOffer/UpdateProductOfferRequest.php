<?php

namespace App\Modules\Product\Http\Requests\Dashboard\ProductOffer;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Product\Application\DTOS\ProductOffer\ProductOfferDTO;

class UpdateProductOfferRequest extends BaseRequestAbstract
{
    protected $dtoClass = ProductOfferDTO::class;
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
        $rules = [];
        $rules['product_offer_id'] = 'required|integer|exists:product_offers,id';
        $rules['is_active'] = 'nullable|boolean';
        $rules['offer_price'] = 'nullable|numeric|min:0.01';
        $rules['product_id'] = 'nullable|integer|exists:products,id';
        $rules['from_date'] = ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'];
        $rules['to_date'] = ['nullable', 'date_format:Y-m-d', 'after_or_equal:from_date'];
        return $rules;

    }
}