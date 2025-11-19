<?php

namespace App\Modules\Auth\Infrastructure\Persistence\Repositories\Admin;

use App\Modules\Auth\Application\DTOS\Admin\LoginDTO;
use App\Modules\Auth\Http\Enums\EmployeeTypeEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Auth\Infrastructure\Persistence\Models\Admin\Employee;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository extends BaseRepositoryAbstract
{
    /**
     * @var Employee
     */
    protected $employee;
    protected $whatsAppApiService;

    public function __construct()
    {
        $this->setModel(new Employee());
    }
    public function login(LoginDTO $loginDTO): Employee
    {
        try {
            $credentials = $loginDTO->toArray();
            $fieldType = array_key_first($credentials);
            $result = $this->getWhere($fieldType, $credentials[$fieldType], 'first');
            $employee = $result instanceof Employee ? $result : null;
            // dd($employee);
            Log::info(['employee' => $employee]);
            if (!$employee || !Hash::check($loginDTO->password, $employee->password)) {
                throw new \Exception('The provided credentials are incorrect.', Response::HTTP_BAD_REQUEST);
            }
            return $employee;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkCredential($checkCredentialDTO): Employee
    {
        try {
            $credentials = $checkCredentialDTO->credential();
            $fieldType = array_key_first($credentials);
            // dd($fieldType);
            // dd($credentials[$fieldType]);
            $employee = $this->getWhere($fieldType, $credentials[$fieldType], 'first');
            if (!$employee) {
                throw new \Exception('The provided credentials are incorrect.');
            }

            return $employee;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function logout($logoutDTO): bool
    {
        try {
            $employee_device = $this->employee->EmployeeDevices()->where('device_token', $logoutDTO->deviceToken)->first();
            if ($employee_device) {
                $employee_device->delete();
            }
            $this->employee->tokens()->delete();
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function sendWhatsAppMessage($phone, $message)
    {
        try {
            $handeledPhone = handelPhone($phone);
            $response = $this->whatsAppApiService->sendMessage($handeledPhone['phone'], $message, 'generalauth', $handeledPhone['countryCode']);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
