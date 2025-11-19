<?php

namespace App\Modules\Order\Http\Requests\Order\Dashboard;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\FileBase64OrFileRule;
use App\Modules\Order\Application\DTOS\Order\OrderDTO;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends BaseRequestAbstract
{
    protected $dtoClass = OrderDTO::class;
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
    public function CustomRules(): array
    {
        return [ // data validation
            'user_id' => 'required|integer|exists:users,id',
            'payment_method_id' => 'nullable|integer|exists:payment_methods,id',
            'invoice_bill' => ['nullable', new FileBase64OrFileRule()],
            'address_id' => ['nullable', 'integer', Rule::exists('user_addresses', 'id')->where('user_id', $this->user_id)],
            'reciept' => ['nullable', new FileBase64OrFileRule()],
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
    /**

     * Prepare the data for validation.

     */

    protected function prepareForValidation(): void

    {

        $this->merge([

            'platform' => 1,

        ]);
    }

    public function customMessages(): array
    {
        return [
            'address_id.exists' => 'Address not found, Please Make Sure that user has address.',
        ];
    }

}
