<?php

namespace App\AddHash\Authentication\Infrastructure\Events\Jwt;

use Symfony\Component\HttpFoundation\RequestStack;
use App\AddHash\AdminPanel\Domain\Captcha\CaptchaCacheInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    private $requestStack;

    private $captchaCache;

    public function __construct(RequestStack $requestStack, CaptchaCacheInterface $captchaCache)
    {
        $this->requestStack = $requestStack;
        $this->captchaCache = $captchaCache;
    }

	public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
	{
        $request = $this->requestStack->getCurrentRequest();
        $ip = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');

        $this->captchaCache->clear($ip, $userAgent);
	}
}