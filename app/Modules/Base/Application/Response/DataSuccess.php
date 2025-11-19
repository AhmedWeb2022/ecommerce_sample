<?php

namespace App\Modules\Base\Application\Response;

class DataSuccess extends DataStatus
{

    public function __construct($data = null, $resourceData = null, array $meta = [], bool $status = true, int $statusCode = 200, string $message = '')
    {
        parent::setData($data);
        parent::setMeta($meta);
        parent::setStatus($status);
        parent::setStatusCode($statusCode);
        parent::setMessage($message);
        parent::setResourceData($resourceData);
    }
}
