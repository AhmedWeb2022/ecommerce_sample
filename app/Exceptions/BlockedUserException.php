<?php


namespace App\Exceptions;

use Exception;

class BlockedUserException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Your account has been blocked by the admin.', \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN);
    }
}
