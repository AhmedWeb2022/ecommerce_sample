<?php

namespace App\Modules\Auth\Http\Requests\Dashboard\Customer;

use App\Modules\Auth\Application\DTOS\Customer\CustomerDTO;
use App\Modules\Auth\Application\DTOS\Customer\UserDTO;
use App\Modules\Auth\Domain\Enums\BusinessRegistrationDetailsTypeEnum;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\FileBase64OrFileRule;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CreateUserRequest extends BaseRequestAbstract
{
    protected  $dtoClass = UserDTO::class;
    /**
     * Determine if the Customer is authorized to make this request.
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
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'image' => ['nullable', new ImageBase64OrFileRule()],
        ];
    }
}
