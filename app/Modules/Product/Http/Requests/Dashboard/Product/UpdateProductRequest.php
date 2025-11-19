<?php

namespace App\Modules\Product\Http\Requests\Dashboard\Product;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Product\Application\DTOS\Product\ProductDTO;
use App\Modules\Product\Domain\Enums\TaxTypeEnum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdateProductRequest extends BaseRequestAbstract
{
    protected $dtoClass = ProductDTO::class;
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
            'product_id' => ['required', 'numeric', Rule::exists('products', 'id')],
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'stock' => 'nullable|numeric',
            'category_id' => 'nullable|integer|exists:categories,id',
            'image' => 'nullable',
        ];
    }
}
