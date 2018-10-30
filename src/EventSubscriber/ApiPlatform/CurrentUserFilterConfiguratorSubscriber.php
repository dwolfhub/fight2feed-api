<?php

namespace App\EventSubscriber\ApiPlatform;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Filter\CurrentUserFilter;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Security;

/**
 * Class CurrentUserFilterConfiguratorSubscriber
 * @package App\EventSubscriber\ApiPlatform
 */
class CurrentUserFilterConfiguratorSubscriber implements EventSubscriberInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var Security
     */
    private $security;

    /**
     * CurrentUserFilterConfiguratorSubscriber constructor.
     *
     * @param EntityManagerInterface $em
     * @param Reader $reader
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $em, Reader $reader, Security $security)
    {
        $this->em = $em;
        $this->reader = $reader;
        $this->security = $security;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        /** @var CurrentUserFilter $filter */
        $filter = $this->em->getFilters()->enable('current_user_filter');
        $filter->setAnnotationReader($this->reader);
        $filter->setSecurity($this->security);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => ['onKernelRequest', EventPriorities::PRE_READ],
        ];
    }
}