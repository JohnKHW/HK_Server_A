<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NotAuthorizedException extends Exception
{

    public function report()
    {
        // Determine if the exception needs custom reporting...

        return false;
    }
    public function render($request)
    {
        // Determine if the exception needs custom rendering...

        return false;
    }
}
