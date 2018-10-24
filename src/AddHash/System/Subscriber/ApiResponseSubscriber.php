<?php

namespace App\AddHash\System\Subscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class ApiResponseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['resolveResponseException', 11],
            ],
        ];
    }

    public function resolveResponseException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $code = $this->getCode($exception);

        $event->setResponse(new JsonResponse(['errors' => $exception->getMessage()], $code));
    }

    private function getCode(\Throwable $exception): int
    {
        if (true === $this->isNoRouteFound($exception)) {
            $code = Response::HTTP_NOT_FOUND;
        } else if (true === $this->isUnauthorized($exception)) {
            $code = Response::HTTP_UNAUTHORIZED;
        } else {
            $code = $exception->getCode()
                ? $exception->getCode()
                : Response::HTTP_BAD_REQUEST;
        }

        return $code;
    }

    private function isNoRouteFound($exception): bool
    {
        $isNotFound = $exception instanceof NotFoundHttpException;
        $isNotAllowed = $exception instanceof MethodNotAllowedHttpException;

        return true === $isNotFound || true === $isNotAllowed;
    }

    private function isUnauthorized($exception)
    {
        return $exception instanceof AuthenticationCredentialsNotFoundException;
    }
}