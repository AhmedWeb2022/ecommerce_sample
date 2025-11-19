<?php

namespace App\Modules\Auth\Http\Requests\Api\Customer;

use App\Modules\Auth\Application\DTOS\Customer\UserAddressDTO;
use App\Modules\Auth\Http\Requests\ApiFormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\FileBase64OrFileRule;
use Illuminate\Validation\Rule;

class CreateOrUpdateUserAddressRequest extends BaseRequestAbstract
{
    protected $dtoClass = UserAddressDTO::class;

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
        return [
            'address' => ['required', 'string'],
            'lat' => ['nullable', 'string'],
            'lng' => ['nullable', 'string'],
            'file_address' => ['nullable', new FileBase64OrFileRule()],
        ];
    }
}
