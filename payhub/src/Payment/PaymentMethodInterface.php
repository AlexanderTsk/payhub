<?php

declare(strict_types=1);

namespace App\Payment;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('payhub.payment_method')]
interface PaymentMethodInterface
{
    public function code(): string;
}
