<?php

declare(strict_types=1);

namespace App\Shared\RequestId;

final class RequestIdStorage
{
    private ?string $requestId = null;

    public function set(string $requestId): void
    {
        $this->requestId = $requestId;
    }

    public function get(): ?string
    {
        return $this->requestId;
    }
}
