<?php

namespace App\Modules\Auth\Http\Requests\Api\Customer;

use App\Modules\Auth\Application\DTOS\Customer\UserAddressDTO;
use App\Modules\Auth\Http\Requests\ApiFormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\FileBase64OrFileRule;
use Illuminate\Validation\Rule;

class CreateOrUpdateUserAnotherAddressRequest extends BaseRequestAbstract
{
    protected $dtoClass = UserAddressDTO::class;

    protected $authUser;
    public function authorize(): bool
    {
        $this->authUser = $this->user();

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
            'another_address' => ['required', 'array'],
            'another_address.*.address' => ['nullable', Rule::requiredIf($this->input('another_address')), 'string'],
            'another_address.*.lat' => ['nullable', 'string'],
            'another_address.*.lng' => ['nullable',],
            'another_address.*.file_address' => ['nullable', new FileBase64OrFileRule()],
            'another_address.*.id' => [
                'nullable',
                'integer',
                Rule::exists('user_addresses', 'id')->where(function ($query) {
                    $query->where('user_id', $this->authUser->id);
                })
            ],
        ];
    }
}
