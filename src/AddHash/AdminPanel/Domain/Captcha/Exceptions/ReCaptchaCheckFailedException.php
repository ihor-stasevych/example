<?php

namespace App\AddHash\AdminPanel\Domain\Captcha\Exceptions;

class ReCaptchaCheckFailedException extends \Exception
{
    protected $code = 400;
}