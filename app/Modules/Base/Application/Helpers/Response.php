<?php

use App\Modules\Base\Application\Response\DataFailed;
use App\Modules\Base\Application\Response\DataSuccess;
use Illuminate\Http\Response;
function DataSuccess($status = true, $message, $data = null, $resourceData = null, $statusCode = 200)
{
    return new DataSuccess(
        status: $status,
        message: $message,
        data: $data,
        resourceData: $resourceData,
        statusCode: $statusCode
    );
}

function DataFailed($status = false, $message, $statusCode = 500)
{
    return new DataFailed(
        status: $status,
        message: $message,
        statusCode: $statusCode
    );
}
