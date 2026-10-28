<?php

declare(strict_types=1);

namespace App\Health;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;

final class DatabaseHealthChecker
{
    private readonly Connection $connection;
    private readonly LoggerInterface $logger;

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function isHealthy(): bool
    {
        try {
            $this->connection->executeQuery('SELECT 1');

            return true;
        } catch (Exception $e) {
            $this->logger->error('Database health check failed', ['exception' => $e]);

            return false;
        }
    }
}
