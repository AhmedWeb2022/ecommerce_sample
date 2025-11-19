<?php

namespace App\Modules\Product\Http\Requests\Dashboard\Product;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Product\Application\DTOS\Product\ProductFilterDTO;

class FetchProductRequest extends BaseRequestAbstract
{
    protected $dtoClass = ProductFilterDTO::class;
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
