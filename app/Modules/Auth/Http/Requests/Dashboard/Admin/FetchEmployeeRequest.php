<?php

namespace App\Modules\Auth\Http\Requests\Dashboard\Admin;

use App\Modules\Auth\Application\DTOS\Admin\EmployeeFilterDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class FetchEmployeeRequest extends BaseRequestAbstract
{
    protected  $dtoClass = EmployeeFilterDTO::class;
    /**
     * Determine if the Employee is authorized to make this request.
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
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'word' => 'nullable|string',
        ];
    }


}
