<?php

namespace Davidmoravek\DoctrineTransactionBundle\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * @author David Moravek
 */
class ControllerResolver implements ControllerResolverInterface
{

    const ORIGINAL_CONTROLLER_KEY = '_originalController';

    /** @var ControllerResolverInterface */
    private $wrapped;

    public function __construct(ControllerResolverInterface $wrapped)
    {
        $this->wrapped = $wrapped;
    }

    /**
     * {@inheritdoc}
     */
    public function getController(Request $request)
    {
        return $this->wrapped->getController($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments(Request $request, $controller)
    {
        if ($request->attributes->has(self::ORIGINAL_CONTROLLER_KEY)) {
            $controller = $request->attributes->get(self::ORIGINAL_CONTROLLER_KEY);
        }

        return $this->wrapped->getArguments($request, $controller);
    }

}