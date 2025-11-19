<?php

namespace App\Modules\Auth\Http\Controllers\Api\Customer;

use App\Modules\Auth\Application\UseCases\Customer\AuthUserUseCase;
use App\Modules\Auth\Http\Requests\Api\Customer\ChangePasswordRequest;
use App\Modules\Auth\Http\Requests\Api\Customer\LogoutRequest;
use App\Modules\Auth\Http\Requests\Api\Customer\UpdateAccountRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Http\Requests\Api\Customer\ChangePhoneRequest;
use App\Modules\Auth\Http\Requests\Api\Customer\DeleteAnotherAddressRequest;

class AuthUserController extends Controller
{
    protected $AuthUserUseCase;

    public function __construct(AuthUserUseCase $AuthUserUseCase)
    {
        $this->AuthUserUseCase = $AuthUserUseCase;
    }

    public function logout(LogoutRequest $request)
    {
        return $this->AuthUserUseCase->logout($request->toDTO())->response();
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        return $this->AuthUserUseCase->changePassword($request->toDTO())->response();
    }
    public function updateAccount(UpdateAccountRequest $request)
    {
        return $this->AuthUserUseCase->updateAccount($request->toDTO())->response();
    }
    public function changePhone(ChangePhoneRequest $request)
    {
        return $this->AuthUserUseCase->changePhone($request->toDTO())->response();
    }
    public function deleteAccount()
    {
        return $this->AuthUserUseCase->deleteAccount()->response();
    }

    public function deleteAnotherAddress(DeleteAnotherAddressRequest $request)
    {
        $dto = $request->toDTO();
        return $this->AuthUserUseCase->deleteAnotherAddress($dto)->response();
    }

    public function checkApproved()
    {
        return $this->AuthUserUseCase->checkExist()->response();
    }

}
