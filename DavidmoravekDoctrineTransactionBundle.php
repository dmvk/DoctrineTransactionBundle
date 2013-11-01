<?php

namespace Davidmoravek\DoctrineTransactionBundle;

use Davidmoravek\DoctrineTransactionBundle\DependencyInjection\Compiler\ControllerResolverPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author David Moravek
 */
class DavidmoravekDoctrineTransactionBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ControllerResolverPass());
    }

}
