<?php

namespace App\AddHash\Authentication\Infrastructure\Events\Jwt;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class onJWTCreatedEvent
{
	/**
	 * @param JWTCreatedEvent $event
	 *
	 * @return void
	 */
	public function onJWTCreated(JWTCreatedEvent $event)
	{
		$expiration = new \DateTime('+1 day');
		$expiration->setTime(2, 0, 0);

		$payload = $event->getData();
		$payload['exp'] = $expiration->getTimestamp();

		$event->setData($payload);
	}
}