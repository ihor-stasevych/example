<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Captcha;

use App\AddHash\AdminPanel\Domain\Captcha\Services\ShowCaptchaServiceInterface;

class ShowCaptchaService extends AbstractCacheService implements ShowCaptchaServiceInterface
{
    private const MAX_COUNT_ATTEMPT = 3;


    public function execute(string $ip, string $userAgent): bool
    {
        $key = $this->generateKey($ip, $userAgent);

        $count = (int)$this->cache->getKey($key);

        $this->cache->add($key, $count + 1);

        return $count > static::MAX_COUNT_ATTEMPT;
    }
}