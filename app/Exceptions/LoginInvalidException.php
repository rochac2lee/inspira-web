<?php

namespace App\Exceptions;

use Exception;

class LoginInvalidException extends Exception
{
    public function render(){
        return response()->json([
            'error'     => class_basename($this),
            'message'   => 'Email e password não estão corretos',
        ], 401);
    }
}
