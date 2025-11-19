<?php

namespace App\Modules\Base\Domain\Request;

use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

abstract class BaseRequestAbstract extends FormRequest implements BaseRequestInterface
{
    /**
     * The DTO class to map the request to
     *
     *
     */
    protected  $dtoClass;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Override in child classes if needed
    }

    /**
     * Get the base validation rules that apply to all requests
     *
     * @return array
     */
    protected function baseRules(): array
    {
        return [
            'paginate' => ['sometimes', 'numeric', 'in:0,1'],
            'limit' => ['sometimes', 'numeric'],
            // Add more global rules as needed
        ];
    }

    /**
     * Get custom validation rules that will be merged with base rules
     *
     * @return array
     */
    public function customRules(): array
    {
        return []; // Override in child classes
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(
            $this->baseRules(),
            $this->customRules()
        );
    }

    /**
     * Validate the request data
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate(): bool
    {
        $validator = $this->getValidatorInstance();

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    /**
     * Get the DTO representation of the request
     *
     * @return BaseDTOInterface
     * @throws \Exception
     */
    public function toDTO(): BaseDTOInterface
    {
        if (!isset($this->dtoClass)) {
            throw new \Exception('DTO class must be specified in the request');
        }

        if (!class_exists($this->dtoClass)) {
            throw new \Exception("DTO class {$this->dtoClass} does not exist");
        }
        return $this->dtoClass::fromArray($this->validated());
    }

    /**
     * Get base validation messages
     *
     * @return array
     */
    protected function baseMessages(): array
    {
        return [
            'paginate.numeric' => 'The :attribute must be a number.',
            'paginate.in' => 'The :attribute must be 0 or 1.',
            'limit.numeric' => 'The :attribute must be a number.',
            // Add more global messages as needed
        ];
    }

    /**
     * Get custom validation messages
     *
     * @return array
     */
    public function customMessages(): array
    {
        return []; // Override in child classes
    }

    /**
     * Get all validation messages
     *
     * @return array
     */
    public function messages(): array
    {
        return array_merge(
            parent::messages(),
            $this->baseMessages(),
            $this->customMessages()
        );
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            $response = $this->buildJsonErrorResponse($validator);
            throw new ValidationException($validator, $response);
        }

        throw new ValidationException($validator);
    }

    /**
     * Build JSON error response for failed validation
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function buildJsonErrorResponse(Validator $validator): JsonResponse
    {
        return new JsonResponse(
            [
                'success' => false,
                'message' =>  $validator->errors()->first() ?? 'Something went wrong. Error',
                'errors' => $validator->errors()->all(),
                'data' => $validator->errors()->messages()
            ],
            422 // Unprocessable Entity
        );
    }
}
