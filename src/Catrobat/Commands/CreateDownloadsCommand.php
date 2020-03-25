<?php

namespace App\Catrobat\Commands;

use App\Catrobat\Commands\Helpers\RemixManipulationProgramManager;
use App\Catrobat\Commands\Helpers\ResetController;
use App\Entity\User;
use App\Entity\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDownloadsCommand extends Command
{
  /**
   * @var UserManager
   */
  private $user_manager;

  /**
   * @var RemixManipulationProgramManager
   */
  private $remix_manipulation_program_manager;

  /**
   * @var ResetController
   */
  private $reset_controller;

  /**
   * CreateDownloadsCommand constructor.
   */
  public function __construct(UserManager $user_manager,
                              RemixManipulationProgramManager $program_manager,
                              ResetController $reset_controller)
  {
    parent::__construct();
    $this->user_manager = $user_manager;
    $this->remix_manipulation_program_manager = $program_manager;
    $this->reset_controller = $reset_controller;
  }

  protected function configure()
  {
    $this->setName('catrobat:download')
      ->setDescription('download a project')
      ->addArgument('program_name', InputArgument::REQUIRED, 'Name of program which gets downloaded')
      ->addArgument('user_name', InputArgument::REQUIRED, 'User who download program')
    ;
  }

  /**
   * @throws \Exception
   *
   * @return int|void|null
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $program_name = $input->getArgument('program_name');
    $user_name = $input->getArgument('user_name');

    $program = $this->remix_manipulation_program_manager->findOneByName($program_name);

    /** @var User */
    $user = $this->user_manager->findUserByUsername($user_name);

    if (null == $program || null == $user || null == $this->reset_controller)
    {
      return;
    }

    try
    {
      /* @var User $user */
      $this->reset_controller->downloadProgram($program, $user);
    }
    catch (\Exception $e)
    {
      return;
    }
    $output->writeln('Downloading '.$program->getName().' with user '.$user->getUsername());
  }
}
