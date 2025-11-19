<?php

namespace App\Modules\Category\Http\Requests\Dashboard\Category;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Category\Application\DTOS\Category\CategoryDTO;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CreateCategoryRequest extends BaseRequestAbstract
{
    protected $dtoClass = CategoryDTO::class;
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
            'image' => ['required', new ImageBase64OrFileRule()],
            'parent_id' => 'nullable|integer|exists:categories,id',
        ];
    }
}
