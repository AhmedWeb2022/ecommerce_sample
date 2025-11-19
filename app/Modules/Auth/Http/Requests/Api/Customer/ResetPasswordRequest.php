<?php

namespace App\Modules\Auth\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Auth\Application\DTOS\Customer\ResetPasswordDTO;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;


class ResetPasswordRequest extends BaseRequestAbstract
{
    protected  $dtoClass = ResetPasswordDTO::class;

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
            'code' => 'required|string',
            'password' => 'required|string|min:6',
        ];
    }


}
