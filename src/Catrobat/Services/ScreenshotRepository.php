<?php

namespace App\Catrobat\Services;

use App\Catrobat\Exceptions\InvalidStorageDirectoryException;
use Imagick;
use ImagickException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;

class ScreenshotRepository
{
  const DEFAULT_SCREENSHOT = 'images/default/screenshot.png';
  const DEFAULT_THUMBNAIL = 'images/default/thumbnail.png';
  /**
   * @var string|string[]|null
   */
  private $thumbnail_dir;
  /**
   * @var string|string[]|null
   */
  private $thumbnail_path;
  /**
   * @var string|string[]|null
   */
  private $screenshot_dir;
  /**
   * @var string|string[]|null
   */
  private $screenshot_path;

  private $imagick;
  /**
   * @var string|string[]|null
   */
  private $tmp_path;
  /**
   * @var string|string[]|null
   */
  private $tmp_dir;

  /**
   * ScreenshotRepository constructor.
   *
   * @param mixed $screenshot_dir
   * @param mixed $screenshot_path
   * @param mixed $thumbnail_dir
   * @param mixed $thumbnail_path
   * @param mixed $tmp_dir
   * @param mixed $tmp_path
   */
  public function __construct($screenshot_dir, $screenshot_path, $thumbnail_dir, $thumbnail_path, $tmp_dir, $tmp_path)
  {
    $screenshot_dir = preg_replace('/([^\/]+)$/', '$1/', $screenshot_dir);
    $screenshot_path = preg_replace('/([^\/]+)$/', '$1/', $screenshot_path);
    $thumbnail_dir = preg_replace('/([^\/]+)$/', '$1/', $thumbnail_dir);
    $thumbnail_path = preg_replace('/([^\/]+)$/', '$1/', $thumbnail_path);
    $tmp_dir = preg_replace('/([^\/]+)$/', '$1/', $tmp_dir);
    $tmp_path = preg_replace('/([^\/]+)$/', '$1/', $tmp_path);

    if (!is_dir($screenshot_dir))
    {
      throw new InvalidStorageDirectoryException($screenshot_dir.' is not a valid directory');
    }
    if (!is_dir($thumbnail_dir))
    {
      throw new InvalidStorageDirectoryException($thumbnail_dir.' is not a valid directory');
    }

    if (!is_dir($tmp_dir))
    {
      throw new InvalidStorageDirectoryException($tmp_dir.' is not a valid directory');
    }

    $this->screenshot_dir = $screenshot_dir;
    $this->thumbnail_dir = $thumbnail_dir;
    $this->tmp_dir = $tmp_dir;

    $this->screenshot_path = $screenshot_path;
    $this->thumbnail_path = $thumbnail_path;
    $this->tmp_path = $tmp_path;
  }

  /**
   * @param mixed $screenshot_filepath
   * @param mixed $id
   *
   * @throws ImagickException
   */
  public function saveProgramAssets($screenshot_filepath, $id)
  {
    $this->saveScreenshot($screenshot_filepath, $id);
    $this->saveThumbnail($screenshot_filepath, $id);
  }

  /**
   * @param mixed $image
   * @param mixed $id
   */
  public function storeImageInTmp($image, $id)
  {
    $filesystem = new Filesystem();
    $tmp_file_path = $this->tmp_dir.$this->generateFileNameFromId($id);
    if ($filesystem->exists($tmp_file_path))
    {
      $filesystem->remove($tmp_file_path);
    }
    $filesystem->copy($image, $tmp_file_path);
  }

  /**
   * @param mixed $image
   * @param mixed $id
   *
   * @throws ImagickException
   */
  public function updateProgramAssets($image, $id)
  {
    $this->storeImageInTmp($image, $id);
    $tmp_file_path = $this->tmp_dir.$this->generateFileNameFromId($id);
    $this->saveScreenshot($tmp_file_path, $id);
    $this->saveThumbnail($tmp_file_path, $id);
  }

  /**
   * @param mixed $filepath
   * @param mixed $id
   *
   * @throws ImagickException
   */
  public function saveScreenshot($filepath, $id)
  {
    $screen = $this->getImagick();
    $screen->readImage($filepath);
    $screen->cropThumbnailImage(480, 480);
    $filename = $this->screenshot_dir.$this->generateFileNameFromId($id);
    if (file_exists($filename))
    {
      unlink($filename);
    }
    $screen->writeImage($filename);
    chmod($filename, 0777);
    $screen->destroy();
  }

  /**
   * @param mixed $id
   */
  public function getScreenshotWebPath($id): string
  {
    if (file_exists($this->screenshot_dir.$this->generateFileNameFromId($id)))
    {
      return $this->screenshot_path.$this->generateFileNameFromId($id);
    }

    return self::DEFAULT_SCREENSHOT;
  }

  /**
   * @param mixed $id
   */
  public function getThumbnailWebPath($id): string
  {
    if (file_exists($this->thumbnail_dir.$this->generateFileNameFromId($id)))
    {
      return $this->thumbnail_path.$this->generateFileNameFromId($id);
    }

    return self::DEFAULT_THUMBNAIL;
  }

  /**
   * @param mixed $screenshot_filepath
   * @param mixed $thumbnail_filepath
   * @param mixed $id
   */
  public function importProgramAssets($screenshot_filepath, $thumbnail_filepath, $id)
  {
    $filesystem = new Filesystem();
    $filesystem->copy($screenshot_filepath, $this->screenshot_dir.$this->generateFileNameFromId($id));
    $filesystem->copy($thumbnail_filepath, $this->thumbnail_dir.$this->generateFileNameFromId($id));
  }

  /**
   * @throws ImagickException
   */
  public function getImagick(): Imagick
  {
    if (null == $this->imagick)
    {
      $this->imagick = new Imagick();
    }

    return $this->imagick;
  }

  /**
   * @param mixed $id
   */
  public function deleteThumbnail($id)
  {
    $this->deleteFiles($this->thumbnail_dir, $id);
  }

  /**
   * @param mixed $id
   */
  public function deleteScreenshot($id)
  {
    $this->deleteFiles($this->screenshot_dir, $id);
  }

  /**
   * @param mixed $screenshot_filepath
   * @param mixed $id
   *
   * @throws ImagickException
   */
  public function saveProgramAssetsTemp($screenshot_filepath, $id)
  {
    $this->saveScreenshotTemp($screenshot_filepath, $id);
    $this->saveThumbnailTemp($screenshot_filepath, $id);
  }

  /**
   * @param mixed $id
   */
  public function makeTempProgramAssetsPerm($id)
  {
    $this->makeScreenshotPerm($id);
    $this->makeThumbnailPerm($id);
  }

  /**
   * @param mixed $id
   */
  public function makeScreenshotPerm($id)
  {
    $filesystem = new Filesystem();
    $filesystem->copy($this->tmp_dir.$this->generateFileNameFromId($id), $this->screenshot_dir.$this->generateFileNameFromId($id));
    $filesystem->remove($this->tmp_dir.$this->generateFileNameFromId($id));
  }

  /**
   * @param mixed $id
   */
  public function makeThumbnailPerm($id)
  {
    $filesystem = new Filesystem();
    $filesystem->copy($this->tmp_dir.'thumb/'.$this->generateFileNameFromId($id), $this->thumbnail_dir.$this->generateFileNameFromId($id));
    $filesystem->remove($this->tmp_dir.'thumb/'.$this->generateFileNameFromId($id));
  }

  /**
   * @param mixed $filepath
   * @param mixed $id
   *
   * @throws ImagickException
   */
  public function saveScreenshotTemp($filepath, $id)
  {
    $screen = $this->getImagick();
    $screen->readImage($filepath);
    $screen->cropThumbnailImage(480, 480);
    $filename = $this->tmp_dir.$this->generateFileNameFromId($id);
    if (file_exists($filename))
    {
      unlink($filename);
    }
    $screen->writeImage($filename);
    chmod($filename, 0777);
    $screen->destroy();
  }

  /**
   * @desc
   *
   * @param mixed $id
   */
  public function deleteTempFilesForProgram($id)
  {
    $fs = new Filesystem();
    $fs->remove(
      [
        $this->tmp_dir.$this->generateFileNameFromId($id),
        $this->tmp_dir.'thumb/'.$this->generateFileNameFromId($id),
        $this->tmp_dir.$id.'.catrobat',
      ]);
  }

  /**
   * @param mixed $id
   */
  public function deletePermProgramAssets($id)
  {
    $this->deleteScreenshot($id);
    $this->deleteThumbnail($id);
    $this->deleteTempFilesForProgram($id);
  }

  /**
   * @desc This function empties the tmp folder.
   *       When this function is used while a user is
   *       uploading a program you will kill the process.
   *       So don't use it. It's for testing purposes.
   */
  public function deleteTempFiles()
  {
    $this->removeDirectory($this->tmp_dir);
  }

  /**
   * @throws ImagickException
   */
  private function saveThumbnail(string $filepath, string $id)
  {
    $thumb = $this->getImagick();
    $thumb->readImage($filepath);
    $thumb->cropThumbnailImage(80, 80);
    $filename = $this->thumbnail_dir.$this->generateFileNameFromId($id);
    if (file_exists($filename))
    {
      unlink($filename);
    }
    $thumb->writeImage($filename);
    chmod($filename, 0777);
    $thumb->destroy();
  }

  private function generateFileNameFromId(string $id): string
  {
    return 'screen_'.$id.'.png';
  }

  private function deleteFiles(string $directory, string $id): void
  {
    try
    {
      $file = new File($directory.$this->generateFileNameFromId($id));
      unlink($file->getPathname());
    }
    catch (FileNotFoundException $e)
    {
    }
  }

  /**
   * @param mixed $filepath
   * @param mixed $id
   *
   * @throws ImagickException
   */
  private function saveThumbnailTemp($filepath, $id)
  {
    $thumb = $this->getImagick();
    $thumb->readImage($filepath);
    $thumb->cropThumbnailImage(80, 80);
    $filename = $this->tmp_dir.'thumb/'.$this->generateFileNameFromId($id);
    if (file_exists($filename))
    {
      unlink($filename);
    }
    $thumb->writeImage($filename);
    chmod($filename, 0777);
    $thumb->destroy();
  }

  /**
   * @param mixed $directory
   */
  private function removeDirectory($directory)
  {
    foreach (glob("{$directory}*") as $file)
    {
      if (is_dir($file))
      {
        $this->recursiveRemoveDirectory($file);
      }
      else
      {
        unlink($file);
      }
    }
  }

  /**
   * @param mixed $directory
   */
  private function recursiveRemoveDirectory($directory)
  {
    foreach (glob("{$directory}/*") as $file)
    {
      if (is_dir($file))
      {
        $this->recursiveRemoveDirectory($file);
      }
      else
      {
        unlink($file);
      }
    }
    rmdir($directory);
  }
}
