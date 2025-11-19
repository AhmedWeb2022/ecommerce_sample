<?php

namespace App\Modules\Category\Http\Requests\Dashboard\Category;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Category\Application\DTOS\Category\CategoryDTO;
use App\Modules\Course\Application\Enums\Setting\SettingStatusEnum;
use App\Modules\Course\Application\Enums\Setting\SettingWatchVideoEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdateCategoryRequest extends BaseRequestAbstract
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
    public function customRules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'image' => ['nullable', new ImageBase64OrFileRule()],
            'parent_id' => 'nullable|integer|exists:categories,id',
        ];
    }
}
