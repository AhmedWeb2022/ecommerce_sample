<?php

namespace App\Modules\Auth\Application\UseCases\Customer;


use App\Modules\Auth\Application\DTOS\Customer\LoginDTO;
use App\Modules\Auth\Application\DTOS\Customer\RegisterDTO;
use App\Modules\Auth\Application\DTOS\Customer\UserDTO;
use App\Modules\Auth\Application\DTOS\Customer\UserFilterDTO;
use App\Modules\Auth\Http\Resources\Customer\UserResource;
use App\Modules\Auth\Http\Resources\Dashboard\Customer\UserDetailsResource as DashboardUserDetailsResource;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\User;
use App\Modules\Auth\Infrastructure\Persistence\Repositories\Customer\UserRepository;
use App\Modules\Base\Application\Response\DataFailed;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Base\Application\Response\DataSuccess;
use App\Modules\Category\Application\Enums\View\ViewTypeEnum;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserUseCase
{

    protected $userRepository;
    /**
     *  @var User $user
     */
    protected $user;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user.
     *
     * @param RegisterDTO $registerDTO
     * @return DataStatus
     */
    public function register(RegisterDTO $registerDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            /** @var User $user */
            $user = $this->userRepository->create($registerDTO);
            if (!$user || !$user->id || $user instanceof Exception) {
                DB::rollBack();
                return new DataFailed(
                    statusCode: Response::HTTP_BAD_REQUEST,
                    message: ' failed to create user'
                );
            }
            $user = $user->fresh();
            if ($user) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $user['token'] = $token;
                DB::commit();
                return new DataSuccess(
                    message: 'success',
                    data: $user,
                    resourceData: new UserResource($user)
                );
            }
            DB::rollBack();
            return new DataFailed(
                statusCode: Response::HTTP_BAD_REQUEST,
                message: ' failed to create user'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return new DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    /**
     * Log in a user.
     *
     * @param LoginDTO $loginDTO
     * @return DataStatus
     */
    public function login(LoginDTO $loginDTO): DataStatus
    {
        try {
            $user = $this->userRepository->login($loginDTO);
            if ($user) {
                $user['token'] = $user->createToken('api_token')->plainTextToken;
                // dd(new UserResource($user));
                return new DataSuccess(
                    message: 'success',
                    data: new UserResource($user)
                );
            }
            return new DataFailed(
                message: 'The provided credentials are incorrect.',
                statusCode: Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return new DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchUsers(UserFilterDTO $userFilterDTO, $viewType = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            // dd($userFilterDTO);
            $users = $this->userRepository->filter(
                dto: $userFilterDTO,
                paginate: $userFilterDTO->paginate,
                limit: $userFilterDTO->limit
            );

            $resource = UserResource::collection($users);

            return new DataSuccess(
                status: true,
                message: 'success',
                data: $userFilterDTO->paginate
                    ? $resource->response()->getData(true)
                    : $resource
            );
        } catch (\Exception $e) {
            return new DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchUserDetails(UserDTO $userDTO, $viewType = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            $user = $this->userRepository->getById($userDTO->user_id);
            $resource = UserResource::make($user);
            return new DataSuccess(
                status: true,
                message: 'success',
                data: $resource
            );
        } catch (\Exception $e) {
            return new DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function createUser(UserDTO $UserDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->create($UserDTO);
            if (!$user || !$user->id || $user instanceof Exception) {
                DB::rollBack();
                return new DataFailed(
                    statusCode: Response::HTTP_BAD_REQUEST,
                    message: ' failed to create user'
                );
            }
            $user = $user->fresh();
            DB::commit();
            return new DataSuccess(
                status: true,
                message: 'success',
                data: new UserResource($user)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return new DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateUser(UserDTO $UserDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->update($UserDTO->user_id, $UserDTO);
            DB::commit();
            return new DataSuccess(
                status: true,
                message: 'success',
                data: new UserResource($user) //DashboardUserResource($user)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return new DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }





    public function deleteUser(UserDTO $UserDTO): DataStatus
    {
        DB::beginTransaction();

        try {
            $user = $this->userRepository->delete($UserDTO->user_id);

            DB::commit();

            return new DataSuccess(
                status: true,
                message: 'success',
                data: true
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return new DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
