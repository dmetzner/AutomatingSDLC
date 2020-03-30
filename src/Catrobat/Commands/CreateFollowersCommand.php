<?php

namespace App\Catrobat\Commands;

use App\Catrobat\Commands\Helpers\RemixManipulationProgramManager;
use App\Catrobat\Services\CatroNotificationService;
use App\Entity\FollowNotification;
use App\Entity\User;
use App\Entity\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateFollowersCommand extends Command
{
  private UserManager $user_manager;

  private RemixManipulationProgramManager $remix_manipulation_program_manager;

  private CatroNotificationService $notification_service;

  private EntityManagerInterface $entity_manager;

  public function __construct(UserManager $user_manager,
                              RemixManipulationProgramManager $program_manager,
                              EntityManagerInterface $entity_manager,
                              CatroNotificationService $notification_service)
  {
    parent::__construct();
    $this->user_manager = $user_manager;
    $this->remix_manipulation_program_manager = $program_manager;
    $this->entity_manager = $entity_manager;
    $this->notification_service = $notification_service;
  }

  protected function configure()
  {
    $this->setName('catrobat:follow')
      ->setDescription('follow an user')
      ->addArgument('user_name', InputArgument::REQUIRED, 'Name of user who gets followed')
      ->addArgument('follower', InputArgument::REQUIRED, 'User who follows')
    ;
  }

  /**
   * @throws Exception
   */
  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $user_name = $input->getArgument('user_name');
    $follower_name = $input->getArgument('follower');

    if ($user_name == $follower_name)
    {
      return -1;
    }

    /** @var User|null $user */
    $user = $this->user_manager->findUserByUsername($user_name);

    /** @var User|null $follower */
    $follower = $this->user_manager->findUserByUsername($follower_name);

    if (null === $user || null === $follower)
    {
      return -1;
    }

    try
    {
      $notification = new FollowNotification($user, $follower);
      $this->followUser($user, $follower);
      $this->notification_service->addNotification($notification);
    }
    catch (Exception $e)
    {
      return -1;
    }
    $output->writeln($follower_name.' follows '.$user_name);

    return 0;
  }

  private function followUser(User $user, User $follower)
  {
    $user->addFollower($follower);
    $follower->addFollowing($user);

    $this->entity_manager->persist($user);
    $this->entity_manager->persist($follower);
    $this->entity_manager->flush();
  }
}
