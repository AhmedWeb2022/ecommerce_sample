<?php

namespace App\Modules\Auth\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use Illuminate\Support\Facades\DB;

class UsernameOrPhoneExists implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table('users')
            ->where('username', $value)
            ->orWhere('phone', $value)
            ->orWhere('email', $value)
            ->exists();

        if (! $exists) {
            $fail('The :attribute does not exist in our records.');
        }
    }
}
