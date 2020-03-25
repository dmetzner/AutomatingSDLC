<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StarterCategoryRepository")
 * @ORM\Table(name="starter_category")
 */
class StarterCategory
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  protected $name;

  /**
   * @ORM\Column(type="string", length=255)
   */
  protected $alias;

  /**
   * @ORM\Column(type="integer", name="order_pos")
   */
  protected $order;

  /**
   * @ORM\OneToMany(targetEntity="Program", mappedBy="category", fetch="EAGER")
   */
  private $programs;

  /**
   * @return string
   */
  public function __toString()
  {
    return (string) $this->alias;
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getPrograms()
  {
    return $this->programs;
  }

  /**
   * @param mixed $programs
   */
  public function setPrograms($programs)
  {
    $this->programs = $programs;
  }

  public function addProgram(Program $program)
  {
    $program->setCategory($this);
  }

  public function removeProgram(Program $program)
  {
    $program->setCategory(null);
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getAlias()
  {
    return $this->alias;
  }

  /**
   * @param mixed $alias
   */
  public function setAlias($alias)
  {
    $this->alias = $alias;
  }

  /**
   * @return mixed
   */
  public function getOrder()
  {
    return $this->order;
  }

  /**
   * @param mixed $order
   */
  public function setOrder($order)
  {
    $this->order = $order;
  }
}
