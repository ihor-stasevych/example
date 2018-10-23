<?php

namespace App\AddHash\System\Subscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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

        $code = $exception->getCode()
            ? $exception->getCode()
            : Response::HTTP_BAD_REQUEST;

        if (true === $this->isNoRouteFound($exception)) {
            $code = Response::HTTP_NOT_FOUND;
        }

        $event->setResponse(new JsonResponse(['errors' => $exception->getMessage()], $code));
    }

    public function isNoRouteFound($exception): bool
    {
        $isNotFound = $exception instanceof NotFoundHttpException;
        $isNotAllowed = $exception instanceof MethodNotAllowedHttpException;

        return true === $isNotFound || true === $isNotAllowed;
    }
}