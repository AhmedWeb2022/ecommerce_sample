<?php
namespace App\Modules\Auth\Http\Requests\Api\Customer;
use App\Modules\Auth\Application\DTOs\Customer\UserDTO;
use App\Modules\Auth\Application\DTOS\Customer\UserFilterDTO;
use App\Modules\Auth\Domain\Repositories\Customer\UserRepositoryInterface;
use App\Modules\Auth\Domain\Entities\UserEntity;
use App\Modules\Auth\Domain\ValueObjects\UserId;
use App\Modules\Auth\Domain\ValueObjects\UserName;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class UserRequest extends BaseRequestAbstract
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
            'base_id' => 'nullable|integer|exists:bases,id',
            'word'=>'nullable|string',
        ];
    }
}
