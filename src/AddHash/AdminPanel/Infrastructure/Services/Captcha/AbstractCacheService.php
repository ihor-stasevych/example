<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Captcha;

use App\AddHash\System\Lib\Cache\CacheInterface;

abstract class AbstractCacheService
{
    protected $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    protected function generateKey(string $ip, string $userAgent): string
    {
        return base64_encode($ip . $userAgent);
    }
}