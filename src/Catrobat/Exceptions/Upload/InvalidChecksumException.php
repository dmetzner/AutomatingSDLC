<?php

namespace App\Catrobat\Exceptions\Upload;

use App\Catrobat\Exceptions\InvalidCatrobatFileException;
use App\Catrobat\StatusCode;

/**
 * Class InvalidChecksumException.
 */
class InvalidChecksumException extends InvalidCatrobatFileException
{
  /**
   * InvalidChecksumException constructor.
   */
  public function __construct()
  {
    parent::__construct('errors.checksum.invalid', StatusCode::INVALID_CHECKSUM);
  }
}
