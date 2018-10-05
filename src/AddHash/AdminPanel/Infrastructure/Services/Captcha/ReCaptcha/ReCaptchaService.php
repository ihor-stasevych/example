<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Captcha\ReCaptcha;

use Symfony\Component\HttpFoundation\RequestStack;
use App\AddHash\AdminPanel\Domain\Captcha\CaptchaCacheInterface;
use App\AddHash\AdminPanel\Domain\Captcha\ReCaptcha\ReCaptchaInterface;
use App\AddHash\AdminPanel\Domain\Captcha\Services\ReCaptcha\ReCaptchaServiceInterface;

class ReCaptchaService implements ReCaptchaServiceInterface
{
    private $requestStack;

    private $captchaCache;

    private $reCaptcha;

    public function __construct(
        RequestStack $requestStack,
        CaptchaCacheInterface $captchaCache,
        ReCaptchaInterface $reCaptcha
    )
    {
        $this->requestStack = $requestStack;
        $this->reCaptcha = $reCaptcha;
        $this->captchaCache = $captchaCache;
    }

    public function execute(?string $response): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        $ip = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');

        $isVerify = true;

        if (true === $this->captchaCache->isShow($ip, $userAgent)) {
            $isVerify = $this->reCaptcha->isVerify($response);
        }

        return $isVerify;
    }
}