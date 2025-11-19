<?php

namespace App\Modules\Auth\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Auth\Application\DTOS\Customer\UpdateAccountDTO;
use App\Modules\Auth\Domain\Enums\BusinessRegistrationDetailsTypeEnum;
use App\Modules\Base\Http\Rules\FileBase64OrFileRule;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends BaseRequestAbstract
{
    protected  $dtoClass = UpdateAccountDTO::class;
    protected $authUser;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->authUser = $this->user();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function customRules(): array
    {
        // dd($this->input('another_address'));

        return [ // data validation
            'name' => ['nullable', 'string'],
            'foundation_name' => ['nullable', 'string', 'max:191'],
            'is_online' => ['nullable', 'boolean'],
            'first_name' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'username' => ['nullable', 'string', 'unique:users,username,' . $this->authUser->id],
            'email' => ['nullable', 'email', 'unique:users,email,' . $this->authUser->id],
            'phone_code' => 'nullable',
            'string',
            'phone' => ['nullable', 'string', 'unique:users,phone,' . $this->authUser->id],
            'location_id' => ['nullable', 'integer'],
            'nationality_id' => ['nullable', 'integer'],
            'allow_notification' => ['nullable', 'boolean'],
            'image' => ['nullable', new ImageBase64OrFileRule()],
            'cover' => ['nullable', new ImageBase64OrFileRule()],
            // 'commercial_register' => ['nullable', new FileBase64OrFileRule()],
            'commercial_register' => ['nullable', 'array'],
            'commercial_register.number' => ['nullable', 'string', 'max:191'],
            'commercial_register.file' => ['required', new FileBase64OrFileRule()],

            // 'tax_register' => ['nullable', new FileBase64OrFileRule()],
            'tax_register' => ['nullable', 'array'],
            'tax_register.number' => ['nullable', 'string', 'max:191'],
            'tax_register.file' => ['required', new FileBase64OrFileRule()],

            'national_address' => ['nullable', 'array'],
            'national_address.address' => ['nullable', Rule::requiredIf($this->input('national_address')), 'string'],
            'national_address.lat' => ['nullable', 'string'],
            'national_address.lng' => ['nullable', 'string'],
            'national_address.file_address' => ['nullable', new FileBase64OrFileRule()],

            'another_address' => ['nullable', 'array'],
            'another_address.*.address' => ['nullable', Rule::requiredIf($this->input('another_address')), 'string'],
            'another_address.*.lat' => ['nullable', 'string'],
            'another_address.*.lng' => ['nullable',],
            'another_address.*.file_address' => ['nullable', new FileBase64OrFileRule()],
            'another_address.*.id' => ['nullable', 'integer', Rule::exists('user_addresses', 'id')->where(function ($query) {
                $query->where('user_id', $this->authUser->id);
            })],
        ];
    }

    /**

     * Prepare the data for validation.

     */

    protected function prepareForValidation(): void
    {

        $data = [];

        if ($this->username) {
            $data['username'] = str_replace(' ', '', strtolower($this->username));
        }
        if ($this->phone) {
            $data['phone'] = str_replace(' ', '', $this->phone);
        }
        if ($this->email) {
            $data['email'] = strtolower($this->email);
        }
        $commercial_register = $this->input('commercial_register', []);
        if ($this->commercial_register) {
            $commercial_register['type'] = BusinessRegistrationDetailsTypeEnum::COMMERCIAL_REGISTER->value;
            if ($this->input('commercial_register.commercial_register_number')) {
                $commercial_register_number = $this->input('commercial_register.commercial_register_number');
                $commercial_register['number'] = $commercial_register_number;
            }
            if ($this->file('commercial_register.commercial_register_file')) {
                $commercial_register['file'] = $this->file('commercial_register.commercial_register_file');
            }elseif (isset($commercial_register['commercial_register_file'])) {
                $commercial_register['file'] = $commercial_register['commercial_register_file'];
            }
            $data['commercial_register'] = $commercial_register;
        }
        $tax_register = $this->input('tax_register', []);
        if ($this->tax_register) {
            $tax_register['type'] = BusinessRegistrationDetailsTypeEnum::TAX_REGISTER->value;
            if (isset($tax_register['tax_register_number'])) {
                $tax_register_number = $tax_register['tax_register_number'];
                $tax_register['number'] = $tax_register_number;
            }
            if ($this->file('tax_register.tax_register_file')) {
                $tax_register['file'] = $this->file('tax_register.tax_register_file');
            } elseif (isset($tax_register['tax_register_file'])) {
                $tax_register['file'] = $tax_register['tax_register_file'];
            }
            $data['tax_register'] = $tax_register;
            Log::info($tax_register);
        }

        // Log::info($this->all());
        // Log::info($this->input('tax_register.tax_register_file'));
        $this->merge($data);

        /* $this->merge(array_filter([
            'username' => $this->username ? str_replace(' ', '', strtolower($this->username)) : null,
            'phone' => $this->phone ? str_replace(' ', '', $this->phone) : null,
            'email' => $this->email ? strtolower($this->email)  : null,
            'national_address.lng' => $this->input('national_address.lon') ?? null,
            'another_address.*.lng' => $this->input('another_address.*.lon') ?? null,
        ])); */
    }
}
