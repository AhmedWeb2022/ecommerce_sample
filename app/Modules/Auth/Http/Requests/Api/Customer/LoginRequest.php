<?php

namespace App\Modules\Auth\Http\Requests\Api\Customer;

use App\Modules\Auth\Application\DTOS\Customer\LoginDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Auth\Http\Rules\UsernameOrPhoneExists;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class LoginRequest extends BaseRequestAbstract
{
    protected  $dtoClass = LoginDTO::class;
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
            'credential' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
