<?php

namespace App\Modules\Base\Domain\Request;

use App\Modules\Base\Domain\DTO\BaseDTOInterface;

interface BaseRequestInterface
{
    /**
     * Validate the request data
     *
     * @return bool
     */
    public function validate(): bool;

    /**
     * Get the DTO representation of the request
     *
     * @return BaseDTOInterface
     */
    public function toDTO(): BaseDTOInterface;

    /**
     * Get validation rules
     *
     * @return array
     */
    public function rules(): array;

    /**
     * Get custom validation messages
     *
     * @return array
     */
    public function messages(): array;
}
