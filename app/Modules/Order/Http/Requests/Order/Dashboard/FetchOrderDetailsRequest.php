<?php

namespace App\Modules\Order\Http\Requests\Order\Dashboard;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Order\Application\DTOS\Order\OrderDTO;
use Illuminate\Foundation\Http\FormRequest;

class FetchOrderDetailsRequest extends BaseRequestAbstract
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
            'order_id' => 'nullable|integer|exists:orders,id',
        ];
    }
}
