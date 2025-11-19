<?php

namespace App\Modules\Auth\Http\Requests\Dashboard\Admin;

use App\Modules\Auth\Application\DTOS\Admin\EmployeeDTO;
use App\Modules\Auth\Http\Enums\EmployeeTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends BaseRequestAbstract
{
    protected  $dtoClass = EmployeeDTO::class;
    public $authEmployee;
    /**
     * Determine if the Employee is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->authEmployee = $this->user();
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
            'employee_id' => ['required', 'numeric', Rule::exists('employees', 'id')],
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:employees,email,' . $this->employee_id,
            'phone' => 'nullable|string|unique:employees,phone,' . $this->employee_id,
            'password' => 'nullable|string|min:6',
            'image' => ['nullable', new ImageBase64OrFileRule()],
        ];
    }
}
