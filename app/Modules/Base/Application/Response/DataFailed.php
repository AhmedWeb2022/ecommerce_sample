<?php

namespace App\Modules\Base\Application\Response;

class DataFailed extends DataStatus
{

    public function __construct(array $errors = [], bool $status = false, int $statusCode = 500, string $message = '')
    {
        parent::setErrors($errors);
        parent::setStatus($status);
        parent::setStatusCode($statusCode);
        parent::setMessage($message);
    }

}
