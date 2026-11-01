<?php

declare(strict_types=1);

namespace App\Payment\Psp;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class PspClient
{
    public function __construct(
        #[Autowire('%env(PSP_API_KEY)%')]
        private readonly string $apiKey,
    ) {
    }

    public function authorizationHeader(): string
    {
        return 'Authorization: Bearer '.$this->apiKey;
    }
}
