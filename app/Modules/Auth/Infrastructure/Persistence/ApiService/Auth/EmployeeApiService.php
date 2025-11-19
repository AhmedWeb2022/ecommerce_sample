<?php

namespace App\Modules\Auth\Infrastructure\Persistence\ApiService\Auth;

use Illuminate\Support\Facades\Http;
use App\Modules\Base\Domain\Support\AuthenticatesViaToken;
use Illuminate\Support\Facades\Log;

class EmployeeApiService implements AuthenticatesViaToken
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.employee.url');
    }

    public function checkAuth(string $token): array
    {
        // dd($this->baseUrl);
        return $this->checkEmployeeAuth($token); // Existing method
    }

    public function fetchTeachers($teacherIds)
    {
        try {
            $payload = [
                'id' => $teacherIds,
                'role' => 2,
            ];
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeader('Accept-Language', request()->header('Accept-Language'))
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'fetch_employees', $payload);
            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function checkEmployeeAuth($token)
    {
        try {
            // dd($token);
            $response = Http::accept('application/json')
                ->contentType('application/json')
                ->withHeaders([
                    'Accept-Language' => request()->header('Accept-Language'),
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->withOptions(['verify' => false]) // 👈 disables SSL verification
                ->post($this->baseUrl . 'check_authentication');
            // dd($response->json());
            return $response->json();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
