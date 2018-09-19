<?php

namespace App\AddHash\AdminPanel\Application\DependencyInjection\User\Miner\Pool\CompilerPass;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class StrategyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $contextDefinition = $container->findDefinition('user.miner.pool.context');

        $strategyServiceIds = array_keys($container->findTaggedServiceIds('user.miner.pool.strategy'));

        foreach ($strategyServiceIds as $strategyServiceId) {
            $contextDefinition->addMethodCall(
                'addStrategy',
                [new Reference($strategyServiceId)]
            );
        }
    }
}