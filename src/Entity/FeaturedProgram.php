<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeaturedRepository")
 * @ORM\EntityListeners({"App\Catrobat\Listeners\Entity\FeaturedProgramImageListener"})
 * @ORM\Table(name="featured")
 */
class FeaturedProgram
{
  /**
   * @var File
   */
  public $file;
  /**
   * @var
   */
  public $removed_id;
  /**
   * @var
   */
  public $old_image_type;

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string")
   */
  protected $imagetype;

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  protected $url;

  /**
   * @ORM\Column(type="boolean")
   */
  protected $active;

  /**
   * @ORM\Column(type="string", options={"default": "pocketcode"})
   */
  protected $flavor = 'pocketcode';

  /**
   * @ORM\Column(type="integer")
   */
  protected $priority = 0;

  /**
   * @ORM\Column(type="boolean", options={"default": false})
   */
  protected $for_ios = false;

  /**
   * @ORM\ManyToOne(targetEntity="Program", fetch="EAGER")
   */
  private $program;

  /**
   * @return mixed
   */
  public function getFlavor()
  {
    return $this->flavor;
  }

  /**
   * @param mixed $flavor
   */
  public function setFlavor($flavor)
  {
    $this->flavor = $flavor;
  }

  /**
   * Get id.
   *
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set image.
   *
   * @param string $image
   *
   * @return FeaturedProgram
   */
  public function setImageType($image)
  {
    $this->imagetype = $image;

    return $this;
  }

  /**
   * Get image.
   *
   * @return string
   */
  public function getImageType()
  {
    return $this->imagetype;
  }

  /**
   * Set program.
   *
   * @param Program $program
   *
   * @return FeaturedProgram
   */
  public function setProgram(Program $program = null)
  {
    $this->program = $program;

    return $this;
  }

  /**
   * Get program.
   *
   * @return \App\Entity\Program
   */
  public function getProgram()
  {
    return $this->program;
  }

  /**
   * @return mixed
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * @param $url
   *
   * @return $this
   */
  public function setUrl($url)
  {
    $this->url = $url;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getActive()
  {
    return $this->active;
  }

  /**
   * @param $active
   *
   * @return $this
   */
  public function setActive($active)
  {
    $this->active = $active;

    return $this;
  }

  public function setNewFeaturedImage(File $file)
  {
    $this->file = $file;
  }

  /**
   * @return mixed
   */
  public function getPriority()
  {
    return $this->priority;
  }

  /**
   * @param mixed $priority
   */
  public function setPriority($priority)
  {
    $this->priority = $priority;
  }

  /**
   * @return mixed
   */
  public function getForIos()
  {
    return $this->for_ios;
  }

  /**
   * @param mixed $for_ios
   */
  public function setForIos($for_ios)
  {
    $this->for_ios = $for_ios;
  }
}
