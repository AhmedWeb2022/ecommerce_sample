<?php

namespace App\Modules\Auth\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\UseCases\Customer\UserUseCase;
use App\Modules\Auth\Http\Requests\Api\Customer\CheckCodeRequest;
use App\Modules\Auth\Http\Requests\Api\Customer\CheckCredentialRequest;
use App\Modules\Auth\Http\Requests\Api\Customer\LoginRequest;

use App\Modules\Auth\Http\Requests\Api\Customer\RegisterRequest;
use App\Modules\Auth\Http\Requests\Api\Customer\ResetPasswordRequest;
use App\Modules\Base\Application\Response\DataSuccess;
use App\Modules\Notification\Application\UseCases\Topic\TopicUseCase;

class UserController extends Controller
{
    protected $userUseCase;

    public function __construct(UserUseCase $userUseCase)
    {
        $this->userUseCase = $userUseCase;
    }


    public function register(RegisterRequest $request)
    {
        $register_response = $this->userUseCase->register($request->toDTO());
        return $register_response->response();
    }

    public function login(LoginRequest $request)
    {
        return $this->userUseCase->login($request->toDTO())->response();
    }

    public function checkCredential(CheckCredentialRequest $request)
    {
        return $this->userUseCase->checkCredential($request->toDTO())->response();
    }


    public function checkCode(CheckCodeRequest $request)
    {
        return $this->userUseCase->checkCode($request->toDTO())->response();
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->userUseCase->resetPassword($request->toDTO())->response();
    }
}
