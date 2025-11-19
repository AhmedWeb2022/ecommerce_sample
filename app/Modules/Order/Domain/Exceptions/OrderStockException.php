<?php

namespace App\Modules\Order\Domain\Exceptions;

use Exception;
use Illuminate\Http\Response;

class OrderStockException extends Exception
{
    public $exceededProducts;

    public function __construct($message, $exceededProducts = [], $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $code);
        $this->exceededProducts = $exceededProducts;
    }
}