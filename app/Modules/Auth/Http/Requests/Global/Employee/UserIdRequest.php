<?php

namespace App\Modules\Auth\Http\Requests\Global\Employee;

use App\Modules\Auth\Application\DTOS\Admin\EmployeeDTO;
use App\Modules\Auth\Application\DTOS\Customer\UserDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class UserIdRequest extends BaseRequestAbstract
{
    protected  $dtoClass = UserDTO::class;
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
            'user_id' => 'required|integer|exists:users,id',
        ];
    }
}
