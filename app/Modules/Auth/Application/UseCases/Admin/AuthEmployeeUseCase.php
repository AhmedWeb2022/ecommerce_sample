<?php

namespace App\Modules\Auth\Application\UseCases\Admin;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Modules\Base\Domain\Holders\EmployeeHolder;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Auth\Application\DTOS\Admin\LogoutDTO;
use App\Modules\Auth\Application\DTOS\Admin\UpdateAccountDTO;
use App\Modules\Auth\Application\DTOS\Admin\EmployeeFilterDTO;
use App\Modules\Auth\Http\Resources\Admin\AdminResource;
use App\Modules\Auth\Infrastructure\Persistence\Models\Admin\Employee;
use App\Modules\Auth\Infrastructure\Persistence\Repositories\Admin\EmployeeRepository;

class AuthEmployeeUseCase
{

    protected $employeeRepository;
    /**
     *  @var Employee
     */

    protected  $employee;


    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }



    // public function logout(LogoutDTO $logoutDTO): DataStatus
    // {
    //     try {
    //         $response = $this->employeeRepository->logout($logoutDTO);
    //         return DataSuccess(
    //             status: true,
    //             message: 'success',
    //             data: $response
    //         );
    //     } catch (\Exception $e) {
    //         return DataFailed(
    //             status: false,
    //             message: $e->getMessage()
    //         );
    //     }
    // }



    public function updateAccount(UpdateAccountDTO $updateAccountDTO): DataStatus
    {
        try {
            $response = $this->employeeRepository->update($this->employee->id, $updateAccountDTO);
            return DataSuccess(
                status: true,
                message: 'success',
                data: new AdminResource($response)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteAccount(): DataStatus
    {
        try {
            $this->employeeRepository->delete($this->employee->id);
            return DataSuccess(
                status: true,
                message: 'success',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
