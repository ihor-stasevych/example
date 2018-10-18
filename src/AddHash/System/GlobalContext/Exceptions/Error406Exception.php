<?php

namespace App\AddHash\System\GlobalContext\Exceptions;

class Error406Exception extends ErrorException
{
    protected $code = 406;

    public function __construct(array $errors)
    {
        parent::__construct($errors);
    }
}