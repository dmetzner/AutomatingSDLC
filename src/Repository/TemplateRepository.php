<?php

namespace App\Repository;

use App\Entity\Template;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TemplateRepository.
 */
class TemplateRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $managerRegistry)
  {
    parent::__construct($managerRegistry, Template::class);
  }

  /**
   * @param $active
   *
   * @return mixed
   */
  public function findByActive($active)
  {
    $qb = $this->createQueryBuilder('e');

    return $qb
      ->select('e')
      ->where($qb->expr()->eq('e.active', $qb->expr()->literal($active)))
      ->getQuery()
      ->getResult()
    ;
  }
}
