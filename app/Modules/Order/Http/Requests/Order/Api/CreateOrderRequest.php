<?php

namespace App\Modules\Order\Http\Requests\Order\Api;

use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Order\Application\DTOS\Order\OrderDTO;
use Illuminate\Validation\Rule ;

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
        $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
        return [ 
        ];
    }
}
