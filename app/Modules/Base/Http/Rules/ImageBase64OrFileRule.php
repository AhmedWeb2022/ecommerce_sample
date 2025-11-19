<?php

namespace App\Modules\Base\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImageBase64OrFileRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_file($value)) {
            $validator = Validator::make(
                ['file' => $value],
                ['file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120']
            );

            if ($validator->fails()) {
                $fail($validator->errors()->first());
            }
        } else {
            // Validate base64 string
            if (!preg_match('/^data:image\/(jpeg|png|jpg|gif|svg|webp)\+?;base64,/', $value)) {
                $fail('The ' . $attribute . ' must be a valid base64 image string.');
            }

            // Check file size (decode base64 and check bytes)
            $base64_size = strlen(base64_decode($value)) / 1024;
            if ($base64_size > 5120) {
                $fail('The ' . $attribute . ' must not be greater than 2048 kilobytes.');
            }
        }
    }
}
