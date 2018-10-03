<?php

namespace App\AddHash\AdminPanel\Domain\Captcha\Services;

interface ShowCaptchaServiceInterface
{
    public function execute(string $ip, string $userAgent): bool;
}