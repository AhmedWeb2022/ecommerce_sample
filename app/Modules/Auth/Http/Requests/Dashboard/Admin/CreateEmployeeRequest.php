<?php

namespace App\Modules\Auth\Http\Requests\Dashboard\Admin;

use App\Modules\Auth\Application\DTOS\Admin\EmployeeDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;

class CreateEmployeeRequest extends BaseRequestAbstract
{
    protected $dtoClass = EmployeeDTO::class;
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
            'name' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|unique:employees,phone',
            'password' => 'required|string|min:6',
            'image' => ['nullable', new ImageBase64OrFileRule()],
        ];
    }
}
