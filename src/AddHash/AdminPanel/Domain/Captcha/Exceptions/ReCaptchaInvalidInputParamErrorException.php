<?php

namespace App\AddHash\AdminPanel\Domain\Captcha\Exceptions;

class ReCaptchaInvalidInputParamErrorException extends \Exception
{
    protected $code = 400;
}