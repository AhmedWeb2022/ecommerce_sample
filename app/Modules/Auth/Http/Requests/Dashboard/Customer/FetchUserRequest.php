<?php

namespace App\Modules\Auth\Http\Requests\Dashboard\Customer;

use App\Modules\Auth\Application\DTOS\Customer\UserFilterDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class FetchUserRequest extends BaseRequestAbstract
{
    protected  $dtoClass = UserFilterDTO::class;
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
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'word' => 'nullable|string',
            'is_approved' => 'nullable|boolean',
            "is_verified" => 'nullable|boolean',
            "is_blocked" => 'nullable|boolean',
            'has_commercial_register' => 'nullable|boolean',
            'has_tax_register' => 'nullable|boolean',
            'has_orders' => 'nullable|boolean',
            'has_current_orders' => 'nullable|boolean',
            "from_date" => 'nullable|date_format:Y-m-d',
            "to_date" => 'nullable|date_format:Y-m-d|after_or_equal:from_date'
        ];
    }
}
