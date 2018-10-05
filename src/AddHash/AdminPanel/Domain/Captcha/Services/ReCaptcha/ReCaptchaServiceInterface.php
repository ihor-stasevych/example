<?php

namespace App\AddHash\AdminPanel\Domain\Captcha\Services\ReCaptcha;

interface ReCaptchaServiceInterface
{
    public function execute(?string $response): bool;
}