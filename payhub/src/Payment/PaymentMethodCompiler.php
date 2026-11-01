<?php

declare(strict_types=1);

namespace App\Payment;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PaymentMethodCompiler implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $paymentMethods = $container->findTaggedServiceIds('payhub.payment_method');
        $references = [];
        foreach ($paymentMethods as $id => $tags) {
            $references[] = new Reference($id);
        }
        $container->getDefinition(PaymentMethodRegistry::class)
                ->setArgument('$paymentMethods', $references);
    }
}
