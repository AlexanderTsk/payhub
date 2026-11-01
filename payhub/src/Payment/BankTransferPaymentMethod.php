<?php

declare(strict_types=1);

namespace App\Payment;

class BankTransferPaymentMethod implements PaymentMethodInterface
{
    public function code(): string
    {
        return 'bank_transfer';
    }
}
