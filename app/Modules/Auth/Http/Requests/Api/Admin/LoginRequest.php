<?php

namespace App\Modules\Auth\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Auth\Http\Rules\UsernameOrPhoneExists;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Auth\Application\DTOS\Admin\LoginDTO;

class LoginRequest extends BaseRequestAbstract
{
    protected  $dtoClass = LoginDTO::class;
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
            // 'username_or_phone' => ['required', 'string', new UsernameOrPhoneExists],
            'phone' => 'nullable|string',
            'password' => 'required|string',
            'credential' => 'nullable',
            'device_id' => 'nullable|string',
            'device_token' => 'nullable|string',
            'device_type' => 'nullable|string',
            'device_os' => 'nullable|string',
            'device_os_version' => 'nullable|string',
        ];
    }
}
