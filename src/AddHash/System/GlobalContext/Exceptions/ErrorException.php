<?php

namespace App\AddHash\System\GlobalContext\Exceptions;

class ErrorException extends \Exception
{
    public function __construct(array $message)
    {
        parent::__construct(json_encode($message));

        $this->message = $message;
    }
}