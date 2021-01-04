<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function report()
    {
        return false;
    }

    public function render($request)
    {
        return response('You are not authorized to access this resource.', 403);
    }
}
