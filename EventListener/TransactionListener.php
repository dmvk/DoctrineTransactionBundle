<?php

namespace Davidmoravek\DoctrineTransactionBundle\EventListener;

use Davidmoravek\DoctrineTransactionBundle\Annotation\Transaction;
use Davidmoravek\DoctrineTransactionBundle\HttpKernel\ControllerResolver;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author David Moravek
 */
class TransactionListener implements EventSubscriberInterface
{

    /** @var \Doctrine\Common\Annotations\Reader */
    private $annotationReader;

    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    public function __construct(Reader $annotationReader, EntityManager $entityManager)
    {
        $this->annotationReader = $annotationReader;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -1337]
        ];
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($originalController = $event->getController())) {
            return;
        }

        $method = new \ReflectionMethod($originalController[0], $originalController[1]);
        foreach ($this->annotationReader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof Transaction) {
                $newController = function () use ($originalController) {
                    try {
                        $this->entityManager->beginTransaction();
                        $response = call_user_func_array($originalController, func_get_args());
                        $this->entityManager->commit();
                        return $response;
                    } catch (\Exception $e) {
                        $this->entityManager->rollback();
                        throw $e;
                    }
                };

                $event->getRequest()->attributes->set(ControllerResolver::ORIGINAL_CONTROLLER_KEY, $originalController);
                $event->setController($newController);
            }
        }
    }

}