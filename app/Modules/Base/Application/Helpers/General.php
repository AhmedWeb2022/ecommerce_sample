<?php

use App\Modules\Auth\Infrastructure\Persistence\Models\User\User;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Support\Str;

function convertToInt($value)
{
    return intval($value);
}


if (!function_exists('generateSlug')) {
    /**
     * Generate a URL-friendly slug from a given string.
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    function generateSlug(string $string, string $separator = '-'): string
    {
        return Str::slug($string, $separator);
    }
}

/**
 * Undocumented function
 *
 * @param [type] $password
 *
 */
function hash_password($password)
{
    return Hash::make($password);
}
function hashUserPassword($password)
{
    return Hash::make($password);
}
function hashApiToken()
{
    return Hash::make(rand(99, 99999999));
}
function userApiToken(Model $model, string $name)
{
    return $model->createToken($name)->plainTextToken;
}
function accessTree()
{
    $jsonFilePath = public_path('Modules/tree/Tree.json');
    return json_decode(file_get_contents($jsonFilePath), true);
}
function ConvertToArray($value): array
{
    $value = is_array($value) ? $value : [$value];
    return $value;
}
function enumCaseValue($enum)
{
    $values = array_map(fn($case) => $case->value, $enum::cases());
    return implode(',', $values);
}

function reflectionClass(string $namespace): object
{
    return (new ReflectionClass($namespace)); //->newInstance();
}
function getAuthWithGuard(string $guard)
{
    return Auth::guard($guard)->user();
}
// function getUserWithPhone(string $phone): User
// {
//     return User::where('phone', $phone)->first();
// }
function generateNumericToken(int $length = 4): string
{
    $i = 0;
    $token = "";

    while ($i < $length) {
        $token .= random_int(0, 9);
        $i++;
    }

    return $token;
}
function generateAlphanumericToken(int $length = 4): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($characters), 0, $length);
}

function checkCredential($credential): array
{
    $email = filter_var($credential, FILTER_VALIDATE_EMAIL) ? $credential : null;
    $phone = !filter_var($credential, FILTER_VALIDATE_EMAIL) ? $credential : null;
    return compact('email', 'phone');
}
function checkCredentialType(array $credentials): string
{
    return array_key_first($credentials);
}
function checkCredentialEmail($credential): bool
{
    return filter_var($credential, FILTER_VALIDATE_EMAIL) ? true : false;
}
function checkCredentialPhone($credential): string
{
    return !filter_var($credential, FILTER_VALIDATE_EMAIL) ? $credential : null;
}

function generateOTP(): string
{
    return rand(100000, 999999);
}

function checkOTP($otp, $cachedOtp): bool
{
    return $otp == $cachedOtp;
}

function handelPhone($phone): array
{
    $countryCodes = [
        '+2' => '2',
        '002' => '2',
        '+966' => '966',
        '00966' => '966'
    ];

    $removedCode = '';
    foreach ($countryCodes as $code => $normalizedCode) {
        if (strpos($phone, $code) === 0) {
            $removedCode = $normalizedCode;
            $phone = substr($phone, strlen($code));
            break;
        }
    }

    // // Replace leading zero with '62' if no country code was removed
    // if ($removedCode === '') {
    //     $phone = preg_replace('/^0/', '62', $phone);
    // }

    return [
        'phone' => $phone,
        'countryCode' => $removedCode
    ];
}


/**
 * @param string $title
 * @return string
 */
function makeSlugFromTitle($title, $model = Product::class): string
{
    $slug = Str::slug($title, '-');

    $count = $model::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

    return $count ? "{$slug}-{$count}" : $slug;
}
