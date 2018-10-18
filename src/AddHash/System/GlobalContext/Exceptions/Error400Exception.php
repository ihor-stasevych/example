<?php

namespace App\AddHash\System\GlobalContext\Exceptions;

class Error400Exception extends ErrorException
{
    protected $code = 400;

    public function __construct(array $errors)
    {
        parent::__construct($errors);
    }
}