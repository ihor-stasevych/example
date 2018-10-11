<?php

namespace App\AddHash\System\Lib\Captcha\ReCaptcha;

interface CaptchaInterface
{
    public function isVerify(?string $response): bool;
}