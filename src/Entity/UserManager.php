<?php

namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Exception\MissingResourceException;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserManager extends \Sonata\UserBundle\Entity\UserManager
{
  public function __construct(PasswordUpdaterInterface $passwordUpdater,
                              CanonicalFieldsUpdater $canonicalFieldsUpdater,
                              EntityManagerInterface $om)
  {
    parent::__construct($passwordUpdater, $canonicalFieldsUpdater, $om, User::class);
  }

  public function isPasswordValid(UserInterface $user, string $password, PasswordEncoderInterface $encoder): bool
  {
    return $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());
  }

  public function getMappedUserData(array $raw_user_data): array
  {
    $response_data = [];

    foreach ($raw_user_data as $user)
    {
      try
      {
        $country = Countries::getName(strtoupper($user->getCountry()));
      }
      catch (MissingResourceException $e)
      {
        $country = '';
      }
      array_push($response_data, [
        'username' => $user->getUsername(),
        'id' => $user->getId(),
        'avatar' => $user->getAvatar(),
        'projects' => count($user->getPrograms()),
        'country' => $country,
        'profile' => $user,
      ]);
    }

    return $response_data;
  }
}
