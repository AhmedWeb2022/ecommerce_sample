<?php

namespace App\Modules\Auth\Infrastructure\Persistence\ApiService\Auth;

use Illuminate\Support\Facades\Http;
use App\Modules\Base\Domain\Support\AuthenticatesViaToken;

class UserApiService implements AuthenticatesViaToken
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.user.url');
    }

    public function checkAuth(string $token): array
    {
        return $this->checkUserAuth($token); // Existing method
    }

    public function checkUserAuth($token)
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
