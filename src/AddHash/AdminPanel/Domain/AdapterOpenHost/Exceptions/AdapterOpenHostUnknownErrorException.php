<?php

namespace App\AddHash\AdminPanel\Domain\AdapterOpenHost\Exceptions;

use App\AddHash\System\GlobalContext\Exceptions\Error406Exception;

class AdapterOpenHostUnknownErrorException extends Error406Exception
{
    protected $code = 400;
}