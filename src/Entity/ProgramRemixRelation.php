<?php

namespace App\Entity;

use App\Utils\TimeUtils;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="program_remix_relation")
 * @ORM\Entity(repositoryClass="App\Repository\ProgramRemixRepository")
 */
class ProgramRemixRelation implements ProgramRemixRelationInterface, ProgramCatrobatRemixRelationInterface
{
  /**
   * -----------------------------------------------------------------------------------------------------------------
   * NOTE: this entity uses a Doctrine workaround in order to allow using foreign keys as primary keys.
   *
   * @link{http://stackoverflow.com/questions/6383964/primary-key-and-foreign-key-with-doctrine-2-at-the-same-time}
   * -----------------------------------------------------------------------------------------------------------------
   */

  /**
   * @ORM\Id
   * @ORM\Column(type="guid")
   */
  protected $ancestor_id;

  /**
   * @ORM\ManyToOne(targetEntity="\App\Entity\Program", inversedBy="catrobat_remix_descendant_relations",
   * fetch="LAZY")
   * @ORM\JoinColumn(name="ancestor_id", referencedColumnName="id")
   *
   * @var Program
   */
  protected $ancestor;

  /**
   * @ORM\Id
   * @ORM\Column(type="guid")
   */
  protected $descendant_id;

  /**
   * @ORM\ManyToOne(targetEntity="\App\Entity\Program", inversedBy="catrobat_remix_ancestor_relations",
   * fetch="LAZY")
   * @ORM\JoinColumn(name="descendant_id", referencedColumnName="id")
   *
   * @var Program
   */
  protected $descendant;

  /**
   * @ORM\Id
   * @ORM\Column(type="integer", nullable=false, options={"default": 0})
   */
  protected $depth = 0;

  /**
   * @ORM\Column(type="datetime")
   */
  protected $created_at;

  /**
   * @var \DateTime
   * @ORM\Column(type="datetime", nullable=true)
   */
  protected $seen_at;

  /**
   * @param int $depth
   */
  public function __construct(Program $ancestor, Program $descendant, $depth)
  {
    $this->setAncestor($ancestor);
    $this->setDescendant($descendant);
    $this->setDepth($depth);
    $this->created_at = null;
    $this->seen_at = null;
  }

  /**
   * @return string
   */
  public function __toString()
  {
    return '(#'.$this->ancestor_id.', #'.$this->descendant_id.', depth: '.$this->depth.')';
  }

  /**
   * @ORM\PrePersist
   *
   * @throws \Exception
   */
  public function updateTimestamps()
  {
    if (null == $this->getCreatedAt())
    {
      $this->setCreatedAt(TimeUtils::getDateTime());
    }
  }

  /**
   * @return ProgramRemixRelation
   */
  public function setAncestor(Program $ancestor)
  {
    $this->ancestor = $ancestor;
    $this->ancestor_id = $ancestor->getId();

    return $this;
  }

  /**
   * @return Program
   */
  public function getAncestor()
  {
    return $this->ancestor;
  }

  /**
   * @return int
   */
  public function getAncestorId()
  {
    return $this->ancestor_id;
  }

  /**
   * @return ProgramRemixRelation
   */
  public function setDescendant(Program $descendant)
  {
    $this->descendant = $descendant;
    $this->descendant_id = $descendant->getId();

    return $this;
  }

  /**
   * @return Program
   */
  public function getDescendant()
  {
    return $this->descendant;
  }

  /**
   * @return int
   */
  public function getDescendantId()
  {
    return $this->descendant_id;
  }

  /**
   * @param int $depth
   *
   * @return ProgramRemixRelation
   */
  public function setDepth($depth)
  {
    $this->depth = (int) $depth;

    return $this;
  }

  /**
   * @return int
   */
  public function getDepth()
  {
    return $this->depth;
  }

  /**
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }

  /**
   * @return $this
   */
  public function setCreatedAt(\DateTime $created_at)
  {
    $this->created_at = $created_at;

    return $this;
  }

  /**
   * @return \DateTime
   */
  public function getSeenAt()
  {
    return $this->seen_at;
  }

  /**
   * @param \DateTime $seen_at
   *
   * @return $this
   */
  public function setSeenAt($seen_at)
  {
    $this->seen_at = $seen_at;

    return $this;
  }

  /**
   * @return string
   */
  public function getUniqueKey()
  {
    return sprintf('ProgramRemixRelation(%d,%d,%d)', $this->ancestor_id, $this->descendant_id, $this->depth);
  }
}
