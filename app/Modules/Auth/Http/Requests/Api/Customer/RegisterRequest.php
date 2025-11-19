<?php

namespace App\Modules\Auth\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Http\Requests\Base\BaseRequest;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Auth\Application\DTOS\Customer\CustomerDTO;
use App\Modules\Auth\Application\DTOS\Customer\RegisterDTO;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\Customer;

class RegisterRequest extends BaseRequestAbstract
{
    protected $dtoClass = RegisterDTO::class;
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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
            'image' => ['nullable', new ImageBase64OrFileRule()],
        ];
    }

    /**

     * Prepare the data for validation.

     */
}
