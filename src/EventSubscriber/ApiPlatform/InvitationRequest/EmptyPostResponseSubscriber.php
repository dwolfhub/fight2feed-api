<?php

namespace App\EventSubscriber\ApiPlatform\InvitationRequest;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\InvitationRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class EmptyPostResponseSubscriber implements EventSubscriberInterface
{
    public function emptyPostResponse(GetResponseForControllerResultEvent $event)
    {
        if ($event->getControllerResult() instanceof InvitationRequest && $event->getRequest()->getMethod() === Request::METHOD_POST) {
            $event->setResponse(new JsonResponse(null, 204));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['emptyPostResponse', EventPriorities::PRE_SERIALIZE],
        ];
    }
}
