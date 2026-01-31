<?php

namespace SqlOrganize\Sql\Exceptions;

use Exception;

/**
 * Excepción para indicar que no puede definirse valor unico
 */
class UniqueException extends Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}