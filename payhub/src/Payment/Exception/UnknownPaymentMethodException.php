<?php

declare(strict_types=1);

namespace App\Payment\Exception;

class UnknownPaymentMethodException extends \InvalidArgumentException
{
    public function __construct(string $code)
    {
        parent::__construct(\sprintf('Payment method "%s" not found.', $code));
    }
}
