<?php

namespace App\Modules\Auth\Infrastructure\Persistence\Repositories\Customer;

use App\Exceptions\BlockedUserException;
use App\Modules\Auth\Application\DTOS\Customer\UpdateAccountDTO;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\PhoneRequest\PhoneRequest;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\User;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Modules\Base\Domain\ApiService\WhatsAppApiService;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Notification\Application\DTOS\Notification\NotificationDTO;
use App\Modules\Notification\Infrastructure\Persistence\ApiService\NotificationApiService;
use App\Modules\Order\Application\Enums\Order\OrderStatusTypeEnum;
use App\Modules\User\Infrastructure\Persistence\ApiService\StageApiService;
use App\Modules\User\Infrastructure\Persistence\ApiService\LocationApiService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;

class UserRepository extends BaseRepositoryAbstract
{
    /**
     * @var User
     */
    protected $user;
    protected $whatsAppApiService;
    protected $stageApiService;
    protected $locationApiService;
    protected $notificationApiService;
    public function __construct()
    {
        $this->setModel(new User());
    }
    public function login($loginDTO): User
    {
        try {
            // dd($loginDTO);
            $credentials = $loginDTO->toArray();
            $fieldType = array_key_first($credentials);
            // dd($credentials, $fieldType);
            $user = $this->getWhere($fieldType, $credentials[$fieldType], 'first');
            if (!isset($user) && PhoneRequest::where($fieldType, $credentials[$fieldType])->exists()) {
                $otp = '123456'; // random_int(100000, 999999);
                setCache("otp_{$loginDTO->phone}", $otp, now()->addMinutes(5));
                $this->sendWhatsAppMessage(phone: $loginDTO->phone, countryCode: $loginDTO->phone_code, message: "Your OTP is: $otp");
                throw new \Exception('The provided credentials are incorrect.', Response::HTTP_BAD_REQUEST);
            } elseif (!isset($user) || !Hash::check($loginDTO->password, $user->password)) {
                throw new \Exception('The provided credentials are incorrect.', Response::HTTP_BAD_REQUEST);
            } elseif ($user->is_blocked) {
                $user->tokens()->delete();
                throw new BlockedUserException();
            }
            return $user;
        } catch (BlockedUserException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function createPhoneRequest($userId, UpdateAccountDTO $updateAccountDTO): User
    {
        try {
            /** @var User $user */
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $user->phoneRequests()->create($updateAccountDTO->toArray());
            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    public function checkCredential($checkCredentialDTO): User|bool
    {
        try {
            $credentials = $checkCredentialDTO->credential();
            $fieldType = array_key_first($credentials);
            // dd($fieldType, $credentials[$fieldType]);
            $is_exist_user = $this->checkExist($fieldType, $credentials[$fieldType], 'first');
            if ($is_exist_user) {
                $user = $this->getWhere($fieldType, $credentials[$fieldType], 'first');
            } elseif (isset($checkCredentialDTO->user_id)) {
                $phoneRequest = PhoneRequest::where('user_id', $checkCredentialDTO->user_id)->where('phone', $credentials['phone'])->first();
                if ($phoneRequest) {
                    $user = $this->getWhere('id', $phoneRequest->user_id, 'first');
                } else {
                    throw new \Exception('The provided credentials are incorrect.', Response::HTTP_BAD_REQUEST);
                }
            } else {
                throw new \Exception('The provided credentials are incorrect.', Response::HTTP_BAD_REQUEST);
            }

            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkChangeRequest($userId, $credentials)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.', Response::HTTP_BAD_REQUEST);
            }
            $user->update($credentials);
            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    public function deleteChangeRequest($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.', Response::HTTP_BAD_REQUEST);
            }
            $user->phoneRequests()->delete();
            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


    public function logout($logoutDTO): bool
    {
        try {
            $user_device = $this->user->userDevices()->where('device_token', $logoutDTO->deviceToken)->first();
            if ($user_device) {
                $user_device->delete();
            }
            $this->user->tokens()->delete();
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function sendWhatsAppMessage($phone, $countryCode, $message)
    {
        try {
            $handeledPhone = handelPhone($phone);
            $handledCountryCode = $handeledPhone['countryCode'] && !empty($handeledPhone['countryCode']) ? $handeledPhone['countryCode'] : handelPhone($countryCode)['countryCode'];
            $response = $this->whatsAppApiService->sendMessage($handeledPhone['phone'], $message, config('services.whatsapp.session'), $handledCountryCode);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getLocation($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $location = $this->locationApiService->fetchLocationDetails($user->location_id);
            if (!$location) {
                throw new \Exception('Location not found.');
            }
            return $location;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    public function getNationality($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $location = $this->locationApiService->fetchLocationDetails($user->nationality_id);
            if (!$location) {
                throw new \Exception('Location not found.');
            }
            return $location;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function forceLogout($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $user->tokens()->delete();
            // $response = $this->notificationApiService->sendNotification(notificationDTO: $notificationDTO, topic: $topic, tokens: $tokens);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function blockUser($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $user->update(['is_blocked' => true]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function unblockUser($userId)
    {
        try {
            $user = $this->getWhere('id', $userId, 'first');
            if (!$user) {
                throw new \Exception('User not found.');
            }
            $user->update(['is_blocked' => false]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
