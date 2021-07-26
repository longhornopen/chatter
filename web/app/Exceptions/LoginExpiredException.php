<?php

namespace App\Exceptions;

use Exception;

class LoginExpiredException extends Exception
{
    public function render($request)
    {
        return response("Your Chatter login has expired.  Please return to your course and relaunch Chatter.", 401);
    }
}
