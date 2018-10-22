<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Filter\ActiveFilter;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ActiveFilterConfiguratorSubscriber implements EventSubscriberInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var Reader
     */
    private $reader;

    public function __construct(EntityManagerInterface $em, Reader $reader)
    {
        $this->em = $em;
        $this->reader = $reader;
    }

    public function onKernelRequest(GetResponseEvent $event): void
    {
        /** @var ActiveFilter $filter */
        $filter = $this->em->getFilters()->enable('active_filter');
        $filter->setAnnotationReader($this->reader);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => ['onKernelRequest', EventPriorities::PRE_READ],
        ];
    }
}
