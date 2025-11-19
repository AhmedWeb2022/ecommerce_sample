<?php

namespace App\Modules\Base\Http\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Application\DTOS\Base\BaseDTO;

class BaseRequest extends FormRequest
{
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
    public function rules(): array
    {
        return [ // data validation
            'base_id' => 'nullable|integer|exists:bases,id',
        ];
    }   

    /**
     * Format the validated data before using it.
     *
     * @return BaseDTO
     */
    public function formatted(): BaseDTO
    {
        $data = BaseDTO::fromArray($this->validated());

        return $data;
    }
}
