<?php

namespace App\AddHash\AdminPanel\Domain\Captcha\ReCaptcha;

interface ReCaptchaInterface
{
    public function isVerify(?string $response): bool;
}