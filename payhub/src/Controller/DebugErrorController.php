<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\Routing\Attribute\Route;

#[When('dev')]
final class DebugErrorController
{
    #[Route('/_debug/error', name: 'debug_error')]
    public function __invoke(): void
    {
        throw new \RuntimeException('boom');
    }
}
