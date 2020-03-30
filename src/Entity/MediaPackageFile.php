<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_package_file")
 */
class MediaPackageFile
{
  public ?File $file = null;

  public ?int $removed_id;

  public ?string $old_extension;

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected ?int $id = null;

  /**
   * @ORM\Column(type="text", nullable=false)
   */
  protected string $name = '';

  /**
   * @ORM\Column(type="string")
   */
  protected string $extension = '';

  /**
   * @ORM\Column(type="text", nullable=true)
   */
  protected ?string $url = null;

  /**
   * @ORM\ManyToOne(targetEntity="MediaPackageCategory", inversedBy="files")
   */
  protected ?MediaPackageCategory $category = null;

  /**
   * @ORM\Column(type="boolean")
   */
  protected bool $active = true;

  /**
   * @ORM\Column(type="integer")
   */
  protected int $downloads = 0;

  /**
   * @ORM\Column(type="string", options={"default": "pocketcode"}, nullable=true)
   */
  protected ?string $flavor = 'pocketcode';

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  protected ?string $author = null;

  public function getActive(): bool
  {
    return $this->active;
  }

  public function setActive(bool $active): MediaPackageFile
  {
    $this->active = $active;

    return $this;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(int $id)
  {
    $this->id = $id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name)
  {
    $this->name = $name;
  }

  public function getUrl(): ?string
  {
    return $this->url;
  }

  public function setUrl(?string $url)
  {
    $this->url = $url;
  }

  public function getCategory(): ?MediaPackageCategory
  {
    return $this->category;
  }

  public function setCategory(MediaPackageCategory $category)
  {
    $this->category = $category;
  }

  public function setExtension(string $extension): MediaPackageFile
  {
    $this->extension = $extension;

    return $this;
  }

  public function getExtension(): string
  {
    return $this->extension;
  }

  public function getFile(): ?File
  {
    return $this->file;
  }

  public function setFile(File $file)
  {
    $this->file = $file;
  }

  public function getRemovedId(): ?int
  {
    return $this->removed_id;
  }

  public function setRemovedId(?int $removed_id)
  {
    $this->removed_id = $removed_id;
  }

  public function getOldExtension(): ?string
  {
    return $this->old_extension;
  }

  public function setOldExtension(string $old_extension)
  {
    $this->old_extension = $old_extension;
  }

  public function getDownloads(): int
  {
    return $this->downloads;
  }

  public function setDownloads(int $downloads): void
  {
    $this->downloads = $downloads;
  }

  public function getFlavor(): ?string
  {
    return $this->flavor;
  }

  public function setFlavor(?string $flavor)
  {
    $this->flavor = $flavor;
  }

  public function getAuthor(): ?string
  {
    return $this->author;
  }

  public function setAuthor(?string $author)
  {
    $this->author = $author;
  }
}
