<?php

namespace App\Modules\Product\Http\Requests\Dashboard\ProductOffer;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Product\Application\DTOS\ProductOffer\ProductOfferDTO;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;

class CreateProductOfferRequest extends BaseRequestAbstract
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

        $rules['is_active'] = 'nullable|boolean';
        $rules['offer_price'] = 'required|numeric|min:0.01';
        $rules['product_id'] = 'required|integer|exists:products,id';
        $rules['from_date'] = ['required', 'date_format:Y-m-d', 'after_or_equal:today'];
        $rules['to_date'] = ['required', 'date_format:Y-m-d', 'after_or_equal:from_date'];
        return $rules;

    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $offerPrice = $this->offer_price;
            $product = Product::find($this->product_id);
            if ($offerPrice > $product->purchase_price) {
                $validator->errors()->add('offer_price', 'Offer price must be less than purchase price.');
            }if (!$product || $product->stock <= 0) {
            $validator->errors()->add('product_id', 'Product is not available or out of stock');
            }
        });
    }


}
