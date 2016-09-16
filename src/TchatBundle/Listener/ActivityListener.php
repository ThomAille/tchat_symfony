<?php

namespace TchatBundle\Listener;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use TchatBundle\Entity\User;

class ActivityListener
{
    protected $context;
    protected $em;

    public function __construct( $context, $manager)
    {
        $this->context = $context;
        $this->em = $manager;
    }

    /**
    * Update the user "lastActivity" on each request
    * @param FilterControllerEvent $event
    */
    public function onCoreController(FilterControllerEvent $event)
    {
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }

        if ($this->context->getToken()) {
            $user = $this->context->getToken()->getUser();
            $delay = new \DateTime();
            $delay->setTimestamp(strtotime('2 minutes ago'));
            if ($user instanceof User && $user->getLastActivity() < $delay) {
                $user->isActiveNow();
                $this->em->flush($user);
            }
        }
    }
}