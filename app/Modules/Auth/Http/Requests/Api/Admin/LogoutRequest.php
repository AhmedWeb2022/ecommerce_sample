<?php

namespace App\Modules\Auth\Http\Requests\Api\Admin;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Auth\Application\DTOS\Admin\LogoutDTO;


class LogoutRequest extends BaseRequestAbstract
{
    protected  $dtoClass = LogoutDTO::class;
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
            'device_token' => 'nullable|string',
        ];
    }


}
