<?php

namespace App\Modules\Auth\Http\Controllers\Dashboard\Customer;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\UseCases\Customer\UserUseCase;

use App\Modules\Auth\Http\Requests\Dashboard\Customer\CreateUserRequest;
use App\Modules\Auth\Http\Requests\Dashboard\Customer\FetchUserRequest;
use App\Modules\Auth\Http\Requests\Dashboard\Customer\UpdateUserRequest;

use App\Modules\Auth\Http\Requests\Global\Employee\UserIdRequest;
use Illuminate\Http\JsonResponse;

use App\Modules\Category\Application\Enums\View\ViewTypeEnum;

class UserController extends Controller
{
    protected $userUseCase;

    public function __construct(UserUseCase $userUseCase)
    {
        $this->userUseCase = $userUseCase;
    }

    public function createUser(CreateUserRequest $request)
    {
        return $this->userUseCase->createUser($request->toDTO())->response();
    }

    public function deleteUser(UserIdRequest $request)
    {
        return $this->userUseCase->deleteUser($request->toDTO())->response();
    }


    public function fetchUsers(FetchUserRequest $request)
    {
        return $this->userUseCase->fetchUsers($request->toDTO(), ViewTypeEnum::DASHBOARD->value)->response();
    }

        public function fetchUserDetails(UserIdRequest $request)
    {
        return $this->userUseCase->fetchUserDetails($request->toDTO(), ViewTypeEnum::DASHBOARD->value)->response();
    }

        public function updateUser(UpdateUserRequest $request)
    {
        return $this->userUseCase->updateUser($request->toDTO())->response();
    }
}
