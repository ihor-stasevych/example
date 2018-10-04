<?php

namespace App\AddHash\AdminPanel\Domain\Captcha;

interface CaptchaCacheInterface
{
    public function isShow(string $ip, string $userAgent, bool $increment = true): bool;

    public function clear(string $ip, string $userAgent);
}