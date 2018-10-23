<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Filter\NotExpiredFilter;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class NotExpiredFilterConfiguratorSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
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
        if (!$event->getRequest()->attributes->get('id')) {
            /** @var NotExpiredFilter $filter */
            $filter = $this->em->getFilters()->enable('not_expired_filter');
            $filter->setAnnotationReader($this->reader);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.request' => ['onKernelRequest', EventPriorities::PRE_READ],
        ];
    }
}
