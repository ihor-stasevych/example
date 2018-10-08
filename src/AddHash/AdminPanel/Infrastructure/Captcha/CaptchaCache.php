<?php

namespace App\AddHash\AdminPanel\Infrastructure\Captcha;

use App\AddHash\System\Lib\Cache\CacheInterface;
use App\AddHash\AdminPanel\Domain\Captcha\CaptchaCacheInterface;

class CaptchaCache implements CaptchaCacheInterface
{
    private const MAX_COUNT_ATTEMPT = 3;

    private const PREFIX = 'captcha_';

    private const LIFE_TIME = 2592000; //30 days


    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function isShow(string $ip, string $userAgent, bool $increment = true): bool
    {
        $key = $this->generateKey($ip, $userAgent);
        $value = (int)$this->cache->getKey($key);

        if (true == $increment) {
            $this->increment($key, $value);
        }

        return $value > static::MAX_COUNT_ATTEMPT;
    }

    public function clear(string $ip, string $userAgent)
    {
        $key = $this->generateKey($ip, $userAgent);

        $this->cache->unsetKey($key);
    }

    private function increment(string $key, int $value)
    {
        $this->cache->add($key, $value + 1, static::LIFE_TIME);
    }

    private function generateKey(string $ip, string $userAgent): string
    {
        return static::PREFIX . base64_encode($ip . $userAgent);
    }
}