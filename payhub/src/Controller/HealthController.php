<?php

declare(strict_types=1);

namespace App\Controller;

use App\Health\DatabaseHealthChecker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class HealthController
{
    private DatabaseHealthChecker $databaseHealthChecker;

    public function __construct(DatabaseHealthChecker $databaseHealthChecker)
    {
        $this->databaseHealthChecker = $databaseHealthChecker;
    }

    #[Route('/health', name: 'health_check', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $healthy = $this->databaseHealthChecker->isHealthy();
        $status = $healthy ? 'ok' : 'fail';
        $checks = ['database' => $healthy ? 'ok' : 'fail'];
        $httpStatus = $healthy ? 200 : 503;

        return new JsonResponse(['status' => $status, 'checks' => $checks], status: $httpStatus);
    }
}
