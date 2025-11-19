<?php

namespace App\Modules\Product\Http\Requests\Dashboard\General\Home;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\General\Application\DTOS\General\Home\HomeFilterDTO;

class FetchHomeRequest extends BaseRequestAbstract
{
    protected  $dtoClass = HomeFilterDTO::class;
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
