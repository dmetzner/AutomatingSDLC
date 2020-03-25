<?php

namespace App\Catrobat\Exceptions\Upload;

use App\Catrobat\Exceptions\InvalidCatrobatFileException;
use App\Catrobat\StatusCode;

/**
 * Class NameTooLongException.
 */
class NameTooLongException extends InvalidCatrobatFileException
{
  /**
   * NameTooLongException constructor.
   */
  public function __construct()
  {
    parent::__construct('errors.name.toolong', StatusCode::PROGRAM_NAME_TOO_LONG);
  }
}
