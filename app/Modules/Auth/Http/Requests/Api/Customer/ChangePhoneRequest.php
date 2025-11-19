<?php

namespace App\Modules\Auth\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Auth\Application\DTOS\Customer\UpdateAccountDTO;
use App\Modules\Base\Http\Rules\FileBase64OrFileRule;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use Illuminate\Validation\Rule;

class ChangePhoneRequest extends BaseRequestAbstract
{
    protected  $dtoClass = UpdateAccountDTO::class;
    protected $authUser;
    /**
     * Determine if the user is authorized to make this request.
     */
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
            // dd($this->input('another_address'));

        return [ // data validation
            'phone_code' => 'required',
            'phone' => ['required', 'string', 'unique:users,phone'],
        ];
    }

    /**

     * Prepare the data for validation.

     */

    protected function prepareForValidation(): void
    {

        $data = [];


        if ($this->phone) {
            $data['phone'] = str_replace(' ', '', $this->phone);
        }
      
        $this->merge($data);

    }
}
