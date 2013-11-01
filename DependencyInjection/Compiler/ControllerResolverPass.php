<?php

namespace Davidmoravek\DoctrineTransactionBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author David Moravek
 */
class ControllerResolverPass implements CompilerPassInterface
{

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('controller_resolver');
        $container->setDefinition('default.controller_resolver', $definition);

        $container->setAlias('controller_resolver', new Alias('davidmoravek_doctrine_transaction.controller_resolver'));
    }

} 