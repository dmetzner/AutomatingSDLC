<?php

namespace App\Catrobat\EventListener;

use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class ResettingSendEmailInitializeSubscriber.
 */
class ResettingSendEmailInitializeSubscriber implements EventSubscriberInterface
{
  /**
   * @var RouterInterface
   */
  private $router;
  /**
   * @var ContainerInterface
   */
  private $container;

  /**
   * ResettingSendEmailInitializeSubscriber constructor.
   */
  public function __construct(RouterInterface $router, ContainerInterface $container)
  {
    $this->router = $router;
    $this->container = $container;
  }

  /**
   * @return GetResponseNullableUserEvent
   */
  public function onResettingSendEmailInitialize(GetResponseNullableUserEvent $event)
  {
    $user = $event->getUser();

    if (null === $user)
    {
      $url = $this->router->generate('reset_invalid');

      return $event->setResponse(new RedirectResponse($url));
    }

    if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl')))
    {
      $url = $this->router->generate('reset_already_requested');

      return $event->setResponse(new RedirectResponse($url));
    }

    return $event;
  }

  /**
   * @return array
   */
  public static function getSubscribedEvents()
  {
    return [
      \FOS\UserBundle\FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE => 'onResettingSendEmailInitialize',
    ];
  }
}
