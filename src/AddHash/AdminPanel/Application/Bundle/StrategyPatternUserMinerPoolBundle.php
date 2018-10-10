<?php

namespace App\AddHash\AdminPanel\Application\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\AddHash\AdminPanel\Application\DependencyInjection\User\Miner\Pool\CompilerPass\StrategyCompilerPass;

class StrategyPatternUserMinerPoolBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new StrategyCompilerPass());
    }
}