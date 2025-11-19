<?php

namespace App\Modules\Category\Http\Requests\Api\Category;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Category\Application\DTOS\Category\CategoryFilterDTO;

class FetchCategoryRequest extends BaseRequestAbstract
{
    protected $dtoClass = CategoryFilterDTO::class;
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
