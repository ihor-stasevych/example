<?php

namespace App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions;

class SSH2AuthFailException extends \Exception
{
    protected $code = 400;
}