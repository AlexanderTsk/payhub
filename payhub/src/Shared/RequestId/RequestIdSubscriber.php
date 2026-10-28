<?php

declare(strict_types=1);

namespace App\Shared\RequestId;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Uid\Uuid;

final class RequestIdSubscriber implements EventSubscriberInterface
{
    private const HEADER = 'X-Request-Id';

    public function __construct(
        private readonly RequestIdStorage $storage,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onRequest', 100],
            KernelEvents::RESPONSE => 'onResponse',
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $incoming = $event->getRequest()->headers->get(self::HEADER);

        $requestId = (null !== $incoming && Uuid::isValid($incoming))
            ? $incoming
            : Uuid::v7()->toRfc4122();

        $this->storage->set($requestId);
    }

    public function onResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $requestId = $this->storage->get();

        if (null === $requestId) {
            return;
        }

        $event->getResponse()->headers->set(self::HEADER, $requestId);
    }
}
