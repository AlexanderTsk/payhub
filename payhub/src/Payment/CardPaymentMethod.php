<?php

declare(strict_types=1);

namespace App\Payment;

class CardPaymentMethod implements PaymentMethodInterface
{
    public function code(): string
    {
        return 'card';
    }
}
