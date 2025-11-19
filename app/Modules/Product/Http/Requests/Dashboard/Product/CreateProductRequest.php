<?php

namespace App\Modules\Product\Http\Requests\Dashboard\Product;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Product\Application\DTOS\Product\ProductDTO;
use App\Modules\Product\Domain\Enums\TaxTypeEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CreateProductRequest extends BaseRequestAbstract
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
    public function CustomRules(): array
    {
        return [
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'image' => 'required',
        ];
    }
}
