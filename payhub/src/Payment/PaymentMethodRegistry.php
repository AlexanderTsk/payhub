<?php

declare(strict_types=1);

namespace App\Payment;

use App\Payment\Exception\UnknownPaymentMethodException;

class PaymentMethodRegistry
{
    /** @var array<string, PaymentMethodInterface> */
    private array $paymentMethods = [];

    /** @param iterable<PaymentMethodInterface> $paymentMethods */
    public function __construct(iterable $paymentMethods)
    {
        foreach ($paymentMethods as $paymentMethod) {
            $this->paymentMethods[$paymentMethod->code()] = $paymentMethod;
        }
    }

    public function get(string $code): PaymentMethodInterface
    {
        if (!isset($this->paymentMethods[$code])) {
            throw new UnknownPaymentMethodException($code);
        }

        return $this->paymentMethods[$code];
    }
}
