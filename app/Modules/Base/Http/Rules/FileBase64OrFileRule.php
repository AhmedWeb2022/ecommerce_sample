<?php

namespace App\Modules\Base\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FileBase64OrFileRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_file($value)) {
            $validator = Validator::make(
                ['file' => $value],
                ['file' => 'mimes:pdf,jpeg,png,jpg,gif,svg+xml,webp|max:5120']
            );

            if ($validator->fails()) {
                $fail($validator->errors()->first());
            }
        } else {
            if (preg_match('/^data:application\/pdf;base64,/', $value)) {
                $base64Str = substr($value, strpos($value, ',') + 1);
                $decoded = base64_decode($base64Str, true);

                if ($decoded === false) {
                    $fail('The ' . $attribute . ' is not a valid base64-encoded file.');
                    return;
                }

                // Check file size (max 5MB = 5120 KB)
                if ((strlen($decoded) / 1024) > 5120) {
                    $fail('The ' . $attribute . ' may not be greater than 5MB.');
                    return;
                }

                // Optionally, check PDF signature
                if (strpos($decoded, '%PDF') !== 0) {
                    $fail('The ' . $attribute . ' must be a valid PDF file.');
                    return;
                }
            }elseif (!preg_match('/^data:image\/(jpeg|png|jpg|gif|svg|webp)\+?;base64,/', $value)) {
                $fail('The ' . $attribute . ' must be a valid base64 image string.');
            } else {
                $fail('The ' . $attribute . ' must be a valid PDF file or base64-encoded PDF.');
            }
        }
    }
}
