<?php

namespace App\AddHash\AdminPanel\Domain\Captcha\Exceptions;

class ReCaptchaNoEnvPrivateKeyErrorException extends \Exception
{
    protected $code = 400;
}