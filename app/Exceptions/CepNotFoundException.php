<?php

namespace App\Exceptions;

use Exception;

class CepNotFoundException extends Exception
{
    protected $message = 'CEP não encontrado.';
    protected $code = 404;
}
