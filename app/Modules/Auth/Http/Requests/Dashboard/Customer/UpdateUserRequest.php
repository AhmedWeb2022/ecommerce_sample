<?php

namespace App\Modules\Auth\Http\Requests\Dashboard\Customer;

use App\Modules\Auth\Application\DTOS\Customer\UserDTO;
use App\Modules\Auth\Domain\Enums\BusinessRegistrationDetailsTypeEnum;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\FileBase64OrFileRule;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseRequestAbstract
{
    protected  $dtoClass = UserDTO::class;
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
            'user_id' => ['required', 'numeric', Rule::exists('users', 'id')],
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $this->user_id,
            'phone' => 'nullable|string|unique:users,phone,' . $this->user_id,
            'password' => 'nullable|string|min:6',
            'image' => ['nullable', new ImageBase64OrFileRule()],
        ];
    }
}
