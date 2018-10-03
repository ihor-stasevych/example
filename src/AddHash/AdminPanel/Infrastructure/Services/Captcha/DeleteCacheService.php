<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Captcha;

use App\AddHash\AdminPanel\Domain\Captcha\Services\DeleteCacheServiceInterface;

class DeleteCacheService extends AbstractCacheService implements DeleteCacheServiceInterface
{
    public function execute(string $ip, string $userAgent)
    {
        $key = $this->generateKey($ip, $userAgent);

        $this->cache->unsetKey($key);
    }
}