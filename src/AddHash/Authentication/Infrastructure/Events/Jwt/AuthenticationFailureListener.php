<?php

namespace App\AddHash\Authentication\Infrastructure\Events\Jwt;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use App\AddHash\Authentication\Domain\Exceptions\UserLogin\UserLoginInvalidVerificationCaptchaException;

class AuthenticationFailureListener
{
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        $response = new JWTAuthenticationFailureResponse();
        $exception = $event->getException()->getPrevious();

        $errors = ['username' => ['Bad credentials']];
        $isCaptcha = $exception instanceof UserLoginInvalidVerificationCaptchaException;

        if (true === $isCaptcha) {
            $errors = $exception->getMessage();
        }

        $response->setJson(json_encode(['errors' => $errors]));

        $event->setResponse($response);
    }
}