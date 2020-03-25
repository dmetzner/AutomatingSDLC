<?php

namespace App\Catrobat\Controller\Admin;

/**
 * Class AdminCommand.
 */
class AdminCommand
{
  /**
   * @var string
   */
  public $name;

  /**
   * @var string
   */
  public $description;

  /**
   * @var
   */
  public $command_link;

  /**
   * @var
   */
  public $progress_link;

  /**
   * @var
   */
  public $command_name;

  /**
   * AdminCommand constructor.
   */
  public function __construct(string $name = '', string $description = '')
  {
    $this->name = $name;
    $this->description = $description;
  }

  /**
   * @param mixed $command
   */
  public function setCommandLink($command)
  {
    $this->command_link = $command;
  }

  /**
   * @param mixed $command
   */
  public function setCommandName($command)
  {
    $this->command_name = $command;
  }

  /**
   * @return mixed
   */
  public function getProgressLink()
  {
    return $this->progress_link;
  }

  /**
   * @param mixed $progress_link
   */
  public function setProgressLink($progress_link)
  {
    $this->progress_link = $progress_link;
  }
}
