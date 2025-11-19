<?php

namespace App\Modules\Product\Http\Requests\Dashboard\ProductOffer;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Product\Application\DTOS\ProductOffer\ProductOfferFilterDTO;

class FetchProductOfferRequest extends BaseRequestAbstract
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
        return [ // data validation
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'word' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            // 'parent_id' => 'nullable|integer|exists:products,id',
            'is_active' => 'nullable|boolean',
            'offer_price' => 'nullable|numeric|min:0',
        ];
    }
}
