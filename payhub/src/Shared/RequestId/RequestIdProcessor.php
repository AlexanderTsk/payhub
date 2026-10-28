<?php

declare(strict_types=1);

namespace App\Shared\RequestId;

use Monolog\Attribute\AsMonologProcessor;
use Monolog\LogRecord;

#[AsMonologProcessor]
final class RequestIdProcessor
{
    public function __construct(
        private readonly RequestIdStorage $storage,
    ) {
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        $requestId = $this->storage->get();
        if (null !== $requestId) {
            $record = $record->with(
                extra: array_merge($record->extra, ['request_id' => $requestId])
            );
        }

        return $record;
    }
}
