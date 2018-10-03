<?php

namespace App\AddHash\AdminPanel\Domain\Captcha\Services;

interface DeleteCacheServiceInterface
{
    public function execute(string $ip, string $userAgent);
}