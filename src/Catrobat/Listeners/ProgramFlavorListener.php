<?php

namespace App\Catrobat\Listeners;

use App\Catrobat\Events\ProgramBeforePersistEvent;
use App\Entity\Program;
use Symfony\Component\HttpFoundation\RequestStack;

class ProgramFlavorListener
{
  /**
   * @var RequestStack
   */
  private $request_stack;

  public function __construct(RequestStack $stack)
  {
    $this->request_stack = $stack;
  }

  public function onEvent(ProgramBeforePersistEvent $event)
  {
    $this->checkFlavor($event->getProgramEntity());
  }

  public function checkFlavor(Program $program)
  {
    $request = $this->request_stack->getCurrentRequest();
    if (null == $request)
    {
      $program->setFlavor('pocketcode');
    }
    else
    {
      $program->setFlavor($request->get('flavor'));
    }
  }
}
