<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="extension")
 * @ORM\Entity(repositoryClass="App\Repository\ExtensionRepository")
 */
class Extension
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected ?int $id = null;

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  protected ?string $name;

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  protected ?string $prefix;

  /**
   * @ORM\ManyToMany(targetEntity="\App\Entity\Program", mappedBy="extensions")
   */
  protected Collection $programs;

  public function __construct()
  {
    $this->programs = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
  }

  public function addProgram(Program $program)
  {
    if ($this->programs->contains($program))
    {
      return;
    }
    $this->programs->add($program);
    $program->addExtension($this);
  }

  public function removeProgram(Program $program)
  {
    if (!$this->programs->contains($program))
    {
      return;
    }
    $this->programs->removeElement($program);
    $program->removeExtension($this);
  }

  public function getPrograms(): Collection
  {
    return $this->programs;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(?string $name)
  {
    $this->name = $name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getPrefix(): ?string
  {
    return $this->prefix;
  }

  public function setPrefix(?string $prefix)
  {
    $this->prefix = $prefix;
  }

  public function removeAllPrograms(): void
  {
    foreach ($this->programs as $program)
    {
      $this->removeProgram($program);
    }
  }
}
