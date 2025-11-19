<?php

namespace App\Modules\Auth\Http\Requests\Api\Customer;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\DTOS\Customer\CheckCredentialDTO;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Http\JsonResponse;
use App\Modules\Auth\Http\Resources\Customer\CustomerResource;

class CheckCredentialRequest extends BaseRequestAbstract
{
    protected  $dtoClass = CheckCredentialDTO::class;
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
            "phone_code" => 'nullable|string', //country_code 
        ];
    }


}
