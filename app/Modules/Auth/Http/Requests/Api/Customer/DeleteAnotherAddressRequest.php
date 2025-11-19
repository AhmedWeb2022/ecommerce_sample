<?php

namespace App\Modules\Auth\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Auth\Application\DTOS\Customer\UpdateAccountDTO;
use App\Modules\Auth\Application\DTOS\Customer\UserAddressDTO;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Http\Rules\FileBase64OrFileRule;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use Illuminate\Validation\Rule;

class DeleteAnotherAddressRequest extends BaseRequestAbstract
{
    protected  $dtoClass = UserAddressDTO::class;
    protected $authUser;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->authUser =  AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
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
            'address_id' => ['required', 'integer', Rule::exists('user_addresses', 'id')->where('user_id', $this->authUser->id)],
        ];
    }

    /**

     * Prepare the data for validation.

     */


}
