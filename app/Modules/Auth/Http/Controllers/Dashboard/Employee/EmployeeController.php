<?php

namespace App\Modules\Auth\Http\Controllers\Dashboard\Employee;

use App\Modules\Auth\Http\Requests\Dashboard\Admin\FetchEmployeeRequest;
use App\Modules\Auth\Application\UseCases\Admin\EmployeeUseCase;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Http\Requests\Api\Admin\LoginRequest;
use App\Modules\Auth\Http\Requests\Api\Admin\LogoutRequest;
use App\Modules\Auth\Http\Requests\Dashboard\Admin\CreateEmployeeRequest;
use App\Modules\Auth\Http\Requests\Dashboard\Admin\UpdateEmployeeRequest;
use App\Modules\Auth\Http\Requests\Global\Employee\EmployeeIdRequest;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    protected $employeeUseCase;

    public function __construct(EmployeeUseCase $employeeUseCase)
    {
        $this->employeeUseCase = $employeeUseCase;
    }

    public function login(LoginRequest $request)
    {
        return $this->employeeUseCase->login($request->toDTO())->response();
    }
        public function logout(LogoutRequest $request)
    {
        return $this->employeeUseCase->logout($request->toDTO())->response();
    }

    public function fetchEmployees(FetchEmployeeRequest $request)
    {

        return $this->employeeUseCase->fetchEmployees($request->toDTO())->response();
    }


    public function fetchEmployeeDetails(EmployeeIdRequest $request)
    {
        return $this->employeeUseCase->fetchEmployeeDetails($request->toDTO())->response();
    }


    public function createEmployee(CreateEmployeeRequest $request)
    {
        return $this->employeeUseCase->createEmployee($request->toDTO())->response();
    }

    public function updateEmployee(UpdateEmployeeRequest $request)
    {
        // dd($request->toDTO());
        Log::info('Update Employee Request', [
            'request' => $request->toDTO()
        ]);
        return $this->employeeUseCase->updateEmployee($request->toDTO())->response();
    }


    public function deleteEmployee(EmployeeIdRequest $request)
    {
        return $this->employeeUseCase->deleteEmployee($request->toDTO())->response();
    }
}
