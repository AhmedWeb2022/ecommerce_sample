<?php

namespace App\Modules\Auth\Http\Requests\Api\Customer;
use App\Modules\Auth\Application\DTOS\Customer\ChangePasswordDTO;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Auth\Http\Rules\CurrentPasswordRule;
use App\Modules\Auth\Http\Rules\NewPasswordRule;


class ChangePasswordRequest extends BaseRequestAbstract
{
    protected  $dtoClass = ChangePasswordDTO::class;
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
            'old_password' => 'required|string',
            'new_password' => 'required|string',
        ];
    }


}

