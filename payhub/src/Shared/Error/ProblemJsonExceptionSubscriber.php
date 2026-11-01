<?php

declare(strict_types=1);

namespace App\Shared\Error;

use App\Shared\RequestId\RequestIdStorage;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class ProblemJsonExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestIdStorage $requestIdStorage,
        #[Autowire('%kernel.debug%')]
        private readonly bool $debug,
        private readonly LoggerInterface $logger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $exception = $event->getThrowable();
        $statusCode = $this->getStatusCode($exception);

        if ($statusCode >= 500) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        } else {
            $this->logger->warning($exception->getMessage(), ['exception' => $exception]);
        }

        $headers = $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];
        $body = [
            'request_id' => $this->requestIdStorage->get(),
            'title' => JsonResponse::$statusTexts[$statusCode],
            'status' => $statusCode,
            'type' => 'about:blank',
        ];
        if ($this->debug) {
            $body['detail'] = $exception->getMessage();
        }

        $event->setResponse(
            new JsonResponse(
                $body,
                $statusCode,
                array_merge(['Content-Type' => 'application/problem+json'], $headers)
            )
        );
    }

    private function getStatusCode(\Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
    }
}
