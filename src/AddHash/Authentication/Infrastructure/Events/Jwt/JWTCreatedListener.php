<?php

namespace App\AddHash\Authentication\Infrastructure\Events\Jwt;

use Symfony\Component\HttpFoundation\RequestStack;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

	public function onJWTCreated(JWTCreatedEvent $event)
	{
		$expiration = new \DateTime('+1 day');
		$expiration->setTime(2, 0, 0);

		$payload = $event->getData();
		$user = $event->getUser();
		$payload['exp'] = $expiration->getTimestamp();
		$payload['x-clientid'] = $user->getId();

		$event->setData($payload);
	}
}